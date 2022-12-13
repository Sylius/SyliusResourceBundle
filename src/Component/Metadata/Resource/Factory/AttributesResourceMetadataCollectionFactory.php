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

use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Reflection\ClassReflection;

final class AttributesResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
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
     */
    private function buildResourceOperations(array $attributes, string $resourceClass): array
    {
        $resources = [];
        $index = -1;

        foreach ($attributes as $attribute) {
            if (is_a($attribute->getName(), Resource::class, true)) {
                /** @var resource $resource */
                $resource = $attribute->newInstance();
                $resources[++$index] = $resource;

                continue;
            }

            if (!is_subclass_of($attribute->getName(), Operation::class)) {
                continue;
            }

            $operationAttribute = $attribute->newInstance();

            [$key, $operation] = $this->getOperationWithDefaults($resources[$index], $operationAttribute);

            $operations = $resources[$index]->getOperations() ?? new Operations();

            $resources[$index] = $resources[$index]->withOperations($operations);
            $resources[$index] = $resources[$index]->withOperations($operations->add($key, $operation));
        }

        return $resources;
    }

    private function getOperationWithDefaults(Resource $resource, Operation $operation): array
    {
        $operationName = $operation->getName();

        return [$operationName, $operation];
    }

    /** @param \ReflectionAttribute[] $attributes */
    private function filterAttributes(array $attributes, $className): array
    {
        return array_filter($attributes, function (\ReflectionAttribute $attribute) use ($className): bool {
            return is_a($attribute->getName(), $className, true);
        });
    }
}
