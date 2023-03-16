<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Metadata\Resource\Factory;

use Sylius\Component\Resource\Grid\State\RequestGridProvider;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Sylius\Component\Resource\Symfony\Request\State\Provider;
use Sylius\Component\Resource\Symfony\Request\State\Responder;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRouteNameFactory;

final class AttributesResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private OperationRouteNameFactory $operationRouteNameFactory,
        private ?string $defaultStateMachineComponent,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $attributes = ClassReflection::getClassAttributes($resourceClass);

        foreach ($this->buildResourceOperations($attributes, $resourceClass) as $resource) {
            $resourceMetadataCollection[] = $resource;
        }

        return $resourceMetadataCollection;
    }

    /**
     * @param \ReflectionAttribute[] $attributes
     *
     * @return ResourceMetadata[]
     */
    private function buildResourceOperations(array $attributes, string $resourceClass): array
    {
        /** @var array<int, ResourceMetadata> $resources */
        $resources = [];
        $index = -1;

        foreach ($attributes as $attribute) {
            if (is_a($attribute->getName(), ResourceMetadata::class, true)) {
                /** @var ResourceMetadata $resource */
                $resource = $attribute->newInstance();
                $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias());
                $resource = $this->getResourceWithDefaults($resourceClass, $resource, $resourceConfiguration);
                $resources[++$index] = $resource;
                $operations = [];

                /** @var Operation $operation */
                foreach ($resource->getOperations() ?? new Operations() as $operation) {
                    [$key, $operation] = $this->getOperationWithDefaults($resources[$index], $operation);
                    $operations[$key] = $operation;
                }

                if ($operations) {
                    $resources[$index] = $resources[$index]->withOperations(new Operations($operations));
                }

                continue;
            }

            if (null === ($resources[$index] ?? null)) {
                try {
                    $resourceConfiguration = $this->resourceRegistry->getByClass($resourceClass);

                    $resource = new ResourceMetadata($resourceConfiguration->getAlias());
                    $resource = $this->getResourceWithDefaults($resourceClass, $resource, $resourceConfiguration);

                    $resources[++$index] = $resource;
                } catch(\InvalidArgumentException) {
                }
            }

            if (!is_subclass_of($attribute->getName(), Operation::class)) {
                continue;
            }

            /** @var Operation $operationAttribute */
            $operationAttribute = $attribute->newInstance();

            [$key, $operation] = $this->getOperationWithDefaults($resources[$index], $operationAttribute);

            $operations = $resources[$index]->getOperations() ?? new Operations();

            $resources[$index] = $resources[$index]->withOperations($operations);
            $resources[$index] = $resources[$index]->withOperations($operations->add($key, $operation));
        }

        return $resources;
    }

    private function getResourceWithDefaults(string $resourceClass, ResourceMetadata $resource, MetadataInterface $resourceConfiguration): ResourceMetadata
    {
        $resource = $resource->withClass($resourceClass);

        if (null === $resource->getApplicationName()) {
            $resource = $resource->withApplicationName($resourceConfiguration->getApplicationName());
        }

        if (null === $resource->getName()) {
            $resource = $resource->withName($resourceConfiguration->getName());
        }

        return $resource;
    }

    private function getOperationWithDefaults(ResourceMetadata $resource, Operation $operation): array
    {
        $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias());

        if (null === $resource->getName()) {
            $resourceName = $resourceConfiguration->getName();

            $resource = $resource->withName($resourceName);
            $operation = $operation->withResource($resource);
        }

        if (null === $resource->getPluralName()) {
            $resourcePluralName = $resourceConfiguration->getPluralName();

            $resource = $resource->withPluralName($resourcePluralName);
            $operation = $operation->withResource($resource);
        }

        if (null === $operation->getTemplate()) {
            $templateDir = $resource->getTemplatesDir() ?? '';
            $template = sprintf('%s/%s.html.twig', $templateDir, $operation->getShortName() ?? '');
            $operation = $operation->withTemplate($template);
        }

        if (null === $operation->getNormalizationContext()) {
            $operation = $operation->withNormalizationContext($resource->getNormalizationContext());
        }

        if (null === $operation->getDenormalizationContext()) {
            $operation = $operation->withDenormalizationContext($resource->getDenormalizationContext());
        }

        $operation = $operation->withResource($resource);

        if (null === $operation->getRepository()) {
            $operation = $operation->withRepository($resourceConfiguration->getServiceId('repository'));
        }

        if (null === $operation->getFormType()) {
            $formType = $resource->getFormType() ?? $resourceConfiguration->getClass('form');
            $operation = $operation->withFormType($formType);
        }

        if (null === $operation->getFormOptions()) {
            $operation = $operation->withFormOptions([
                'data_class' => $resourceConfiguration->getClass('model'),
            ]);
        }

        if (
            $operation instanceof StateMachineAwareOperationInterface &&
            null === $operation->getStateMachineComponent() &&
            method_exists($resourceConfiguration, 'getStateMachineComponent')
        ) {
            $stateMachineComponent = $resourceConfiguration->getStateMachineComponent() ?? $this->defaultStateMachineComponent;

            /** @var Operation $operation */
            $operation = $operation->withStateMachineComponent($stateMachineComponent);
        }

        if ($operation instanceof HttpOperation) {
            if (null === $operation->getRoutePrefix()) {
                $operation = $operation->withRoutePrefix($resource->getRoutePrefix() ?? null);
            }

            if (null === $routeName = $operation->getRouteName()) {
                $routeName = $this->operationRouteNameFactory->createRouteName($operation);
                $operation = $operation->withRouteName($routeName);
            }

            if (null === $operation->getProvider() && null !== $operation->getGrid()) {
                $operation = $operation->withProvider(RequestGridProvider::class);
            }

            if (null === $operation->getProvider()) {
                $operation = $operation->withProvider(Provider::class);
            }

            if (null === $operation->getResponder()) {
                $operation = $operation->withResponder(Responder::class);
            }

            $operation = $operation->withName($routeName);
        }

        $operationName = $operation->getName();

        return [$operationName, $operation];
    }
}
