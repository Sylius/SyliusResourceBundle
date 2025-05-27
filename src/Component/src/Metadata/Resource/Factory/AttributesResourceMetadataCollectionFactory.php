<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Resource\Metadata\Resource\Factory;

use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Reflection\ClassReflection;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;

final class AttributesResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    use OperationDefaultsTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private OperationRouteNameFactory $operationRouteNameFactory,
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
            if (is_a($attribute->getName(), AsResource::class, true)) {
                /** @var AsResource $resourceAttribute */
                $resourceAttribute = $attribute->newInstance();
                $resource = $resourceAttribute->toMetadata();

                $resourceAlias = $resource->getAlias();

                if (null !== $resourceAlias) {
                    $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias() ?? '');
                } else {
                    $resourceConfiguration = $this->resourceRegistry->getByClass($resourceClass);
                }

                $resource = $this->getResourceWithDefaults($resourceClass, $resource, $resourceConfiguration);
                $resources[++$index] = $resource;
                $operations = [];

                /** @var Operation $operation */
                foreach ($resource->getOperations() ?? new Operations() as $operation) {
                    [$key, $operation] = $this->getOperationWithDefaults($operation, $resources[$index], $this->operationRouteNameFactory, $this->resourceRegistry);
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

            [$key, $operation] = $this->getOperationWithDefaults($operationAttribute, $resources[$index], $this->operationRouteNameFactory, $this->resourceRegistry);

            $operations = $resources[$index]->getOperations() ?? new Operations();

            $resources[$index] = $resources[$index]->withOperations($operations);
            $resources[$index] = $resources[$index]->withOperations($operations->add($key, $operation));
        }

        return $resources;
    }
}
