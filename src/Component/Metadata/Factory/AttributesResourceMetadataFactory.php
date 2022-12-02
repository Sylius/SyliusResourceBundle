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
use Webmozart\Assert\Assert;

final class AttributesResourceMetadataFactory implements ResourceMetadataFactoryInterface
{
    public function __construct(private OperationFactoryInterface $operationFactory)
    {
    }

    public function create(string $className): ResourceMetadata
    {
        $resourceMetadata = new ResourceMetadata(new Resource());
        $resource = $resourceMetadata->getResource();

        if (null === $resource) {
            return $resourceMetadata;
        }

        $attributes = ClassReflection::getClassAttributes($className);
        $resourceArguments = $this->getResourceArguments($attributes);

        $resource = $this->createMetadataWithOperations($className, $resource, $resourceArguments, $attributes);

        return $resourceMetadata->withResource($resource);
    }

    private function createMetadataWithOperations(
        string $className,
        Resource $resource,
        array $resourceArguments,
        array $attributes,
    ): Resource {
        $operations = $resource->getOperations() ?? new Operations();
        $operationAttributes = $this->filterAttributes($attributes, Operation::class);

        /** @var \ReflectionAttribute $attribute */
        foreach ($operationAttributes as $attribute) {
            $operationArguments = $attribute->getArguments();

            /** @var class-string $operationClassName */
            $operationClassName = $attribute->getName();
            $operation = $this->operationFactory->create($operationClassName, $operationArguments);

            $operationName = $operation->getName();
            Assert::notNull($operationName, sprintf('Operation %s should have a name in %s', $operationClassName, $className));

            $operations->add($operationName, $operation);
        }

        if (null !== $alias = ($resourceArguments['alias'] ?? null)) {
            $resource = $resource->withAlias($alias);
        }

        return $resource->withOperations($operations);
    }

    private function getResourceArguments(array $attributes): array
    {
        $resourceAttributes = $this->filterAttributes($attributes, Resource::class);

        foreach ($resourceAttributes as $resourceAttribute) {
            return $resourceAttribute->getArguments();
        }

        return [];
    }

    /**
     * @param \ReflectionAttribute[] $attributes
     * @param class-string $className
     */
    private function filterAttributes(array $attributes, string $className): array
    {
        return array_filter($attributes, function (\ReflectionAttribute $attribute) use ($className): bool {
            return is_a($attribute->getName(), $className, true);
        });
    }
}
