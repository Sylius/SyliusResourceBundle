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

use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Reflection\ClassReflection;

final class AttributesResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(private RegistryInterface $resourceRegistry)
    {
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
        /** @var ResourceMetadata[] $resources */
        $resources = [];
        $index = -1;

        foreach ($attributes as $attribute) {
            if (is_a($attribute->getName(), ResourceMetadata::class, true)) {
                /** @var ResourceMetadata $resource */
                $resource = $attribute->newInstance();
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
                $metadata = $this->resourceRegistry->getByClass($resourceClass);

                $resources[++$index] = new Resource($metadata->getAlias());
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

    private function getOperationWithDefaults(ResourceMetadata $resource, Operation $operation): array
    {
        if (null !== $section = $resource->getSection()) {
            $operation = $operation->withSection($section);
        }

        if ($operation instanceof HttpOperation) {
            if (null === $routeName = $operation->getRouteName()) {
                $routeName = $this->getDefaultRouteName($resource, $operation);
                $operation = $operation->withRouteName($routeName);
            }

            $operation = $operation->withName($routeName);
        }

        $operationName = $operation->getName();

        return [$operationName, $operation];
    }

    private function getDefaultRouteName(ResourceMetadata $resource, HttpOperation $operation): string
    {
        $resourceConfiguration = $this->resourceRegistry->get($resource->getAlias());

        if (null !== $section = $operation->getSection()) {
            $section = '_' . $section;
        }

        if (null === $shortName = $operation->getShortName()) {
            throw new \RuntimeException(sprintf('Operation "%s" should have a short name to build a route name', $operation::class));
        }

        return sprintf('%s%s_%s_%s', $resourceConfiguration->getApplicationName(), $section ?? '', $resourceConfiguration->getName(), $shortName);
    }
}
