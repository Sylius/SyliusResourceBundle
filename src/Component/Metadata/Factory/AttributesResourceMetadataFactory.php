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

use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Metadata\Section;
use Sylius\Component\Resource\Metadata\Sections;
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

        $resource = $this->createMetadataWithSections($resource, $resourceArguments, $attributes);
        $resource = $this->createMetadataWithOperations($resource, $resourceArguments, $attributes);

        return $resourceMetadata->withResource($resource);
    }

    private function createMetadataWithSections(
        Resource $resource,
        array $resourceArguments,
        array $attributes
    ): Resource {
        $operations = $resource->getOperations() ?? new Operations();

        $sectionAttributes = $this->filterAttributes($attributes, Section::class);

        foreach ($sectionAttributes as $attribute) {
            $arguments = $attribute->getArguments();

            $section = new Section(
                name: $arguments['name'],
                routePrefix: $arguments['routePrefix'] ?? null,
                templatesDir: $arguments['templatesDir'] ?? null,
            );

            /** @var Operation $operation */
            foreach ($arguments['operations'] as $operation) {
                $operation = $operation->withSection($section->getName());

                if (
                    null === $operation->getRoutePrefix() &&
                    null !== $routePrefix = $section->getRoutePrefix()
                ) {
                    $operation = $operation->withRoutePrefix($routePrefix);
                }

                if (
                    null === $operation->getTemplate() &&
                    null !== ($templatesDir = $section->getTemplatesDir()) && !($operation instanceof DeleteOperationInterface)
                ) {
                    $operation = $operation->withTemplate(sprintf('%s/%s.html.twig', $templatesDir, $operation->getName()));
                }

                if (
                    null === $operation->getResource() &&
                    null !== $alias = $resourceArguments['alias']
                ) {
                    $operation = $operation->withResource($alias);
                }

                $operations->add($operation->getName(), $operation);
                $section = $section->withOperations($operations);
            }
        }

        return $resource->withOperations($operations);
    }

    private function createMetadataWithOperations(
        Resource $resource,
        array $resourceArguments,
        array $attributes
    ): Resource {
        $operations = $resource->getOperations() ?? new Operations();
        $operationAttributes = $this->filterAttributes($attributes, Operation::class);

        foreach ($operationAttributes as $attribute) {
            $arguments = array_merge($attribute->getArguments(), $resourceArguments);
            $arguments['resource'] = $arguments['resource'] ?? $arguments['alias'];
            unset($arguments['alias']);

            $operation = $this->operationFactory->create($attribute->getName(), $arguments);
            $operations->add($operation->getName(), $operation);
        }

        if (null !== $alias = ($resourceArguments['alias'] ?? null)) {
            $resource = $resource->withAlias($alias);
        }

        return $resource->withOperations($operations);
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
