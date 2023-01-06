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
                throw new \RuntimeException(sprintf('No Resource attribute was found on %s', $resourceClass));
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

    private function getOperationWithDefaults(Resource $resource, Operation $operation): array
    {
        $operationName = $operation->getName();

        if (null !== $section = $resource->getSection()) {
            $operation = $operation->withSection($section);
        }

        if (
            null === $operation->getTemplate() &&
            null !== $templateDir = $resource->getTemplatesDir()
        ) {
            $operation = $operation->withTemplate(sprintf('%s/%s.html.twig', $templateDir, $operation->getName() ?? ''));
        }

        if (!$operation instanceof HttpOperation) {
            return [$operationName, $operation];
        }

        if (
            null === $operation->getRoutePrefix() &&
            null !== $routePrefix = $resource->getRoutePrefix()
        ) {
            $operation = $operation->withRoutePrefix($routePrefix);
        }

        return [$operationName, $operation];
    }
}
