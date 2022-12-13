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
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\Section;
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
        $section = null;

        foreach ($attributes as $attribute) {
            if (is_a($attribute->getName(), Resource::class, true)) {
                /** @var resource $resource */
                $resource = $attribute->newInstance();
                $resources[++$index] = $resource;

                continue;
            }

            if (is_a($attribute->getName(), Section::class, true)) {
                /** @var Section $section */
                $section = $attribute->newInstance();

                $operations = [];

                foreach ($section->getOperations() ?? new Operations() as $operation) {
                    [$key, $operation] = $this->getOperationWithDefaults($resources[$index], $operation, $section);
                    $operations[$key] = $operation;
                }

                if ($operations) {
                    $resources[$index] = $resources[$index]->withOperations(new Operations($operations));
                }

                continue;
            }

            if (!is_subclass_of($attribute->getName(), Operation::class)) {
                continue;
            }

            $operationAttribute = $attribute->newInstance();

            [$key, $operation] = $this->getOperationWithDefaults($resources[$index], $operationAttribute, $section);

            $operations = $resources[$index]->getOperations() ?? new Operations();

            $resources[$index] = $resources[$index]->withOperations($operations);
            $resources[$index] = $resources[$index]->withOperations($operations->add($key, $operation));
        }

        return $resources;
    }

    private function getOperationWithDefaults(Resource $resource, Operation $operation, ?Section $section = null): array
    {
        $operationName = $operation->getName();

        if (null === $section) {
            return [$operationName, $operation];
        }

        if (null === $operation->getSection()) {
            $operation = $operation->withSection($section->getName());
        }

        if (
            null === $operation->getTemplate() &&
            null !== $templateDir = $section->getTemplatesDir()
        ) {
            $operation = $operation->withTemplate(sprintf('%s/%s.html.twig', $templateDir, $operation->getName() ?? ''));
        }

        if (!$operation instanceof HttpOperation) {
            return [$operationName, $operation];
        }

        if (
            null === $operation->getRoutePrefix() &&
            null !== $sectionRoutePrefix = $section->getRoutePrefix()
        ) {
            $operation = $operation->withRoutePrefix($sectionRoutePrefix);
        }

        return [$operationName, $operation];
    }
}
