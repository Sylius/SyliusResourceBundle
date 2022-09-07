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

namespace Sylius\Component\Resource\Metadata\Factory;

use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Reflection\ClassReflection;

final class AttributesResourceMetadataFactory implements ResourceMetadataFactoryInterface
{
    public function __construct(private OperationFactoryInterface $operationFactory)
    {
    }

    public function create(string $className): ResourceMetadata
    {
        $resourceMetadata = new ResourceMetadata(new Resource());
        $resource = $resourceMetadata->getResource();

        $attributes = ClassReflection::getClassAttributes($className);
        $resourceArguments = $this->getResourceArguments($attributes);
        $operationAttributes = $this->filterAttributes($attributes, Operation::class);

        $operations = [];

        foreach ($operationAttributes as $attribute) {
            $arguments = array_merge($attribute->getArguments(), $resourceArguments);
            $arguments['resource'] = $arguments['resource'] ?? $arguments['alias'];
            unset($arguments['alias']);

            $operation = $this->operationFactory->create($attribute->getName(), $arguments);
            $operations[] = $operation;
        }

        if (null !== $alias = ($resourceArguments['alias'] ?? null)) {
            $resource = $resource->withAlias($alias);
        }

        $resource = $resource->withOperations(new Operations($operations));

        return $resourceMetadata->withResource($resource);
    }

    private function getResourceArguments($attributes): array
    {
        $resourceAttributes = $this->filterAttributes($attributes, Resource::class);

        foreach ($resourceAttributes as $resourceAttribute) {
            return $resourceAttribute->getArguments();
        }

        return [];
    }

    /** @param \ReflectionAttribute[] $attributes */
    private function filterAttributes(array $attributes, $className): array
    {
        return array_filter($attributes, function (\ReflectionAttribute $attribute) use ($className): bool {
            return is_a($attribute->getName(), $className, true);
        });
    }
}
