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

namespace Sylius\Resource\Symfony\Routing\Factory\Resource;

use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactoryInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class ResourceRouteCollectionFactory implements ResourceRouteCollectionFactoryInterface
{
    public function __construct(
        private readonly OperationRouteFactoryInterface $operationRouteFactory,
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataFactory,
        private readonly RegistryInterface $resourceRegistry,
    ) {
    }

    public function createRouteCollectionForClass(string $className): RouteCollection
    {
        $routeCollection = new RouteCollection();
        $resourceMetadata = $this->resourceMetadataFactory->create($className);

        /** @var ResourceMetadata $resource */
        foreach ($resourceMetadata->getIterator() as $resource) {
            $this->createRoutesForResource($routeCollection, $resource);
        }

        return $routeCollection;
    }

    private function createRoutesForResource(RouteCollection $routeCollection, ResourceMetadata $resource): void
    {
        foreach ($resource->getOperations() ?? new Operations() as $operation) {
            if (!$operation instanceof HttpOperation) {
                continue;
            }

            $this->addRouteForOperation($routeCollection, $resource, $operation);
        }
    }

    private function addRouteForOperation(RouteCollection $routeCollection, ResourceMetadata $resource, HttpOperation $operation): void
    {
        $alias = $resource->getAlias();
        Assert::notNull($alias, sprintf('Resource of %s has no alias.', $resource->getClass() ?? ''));

        $metadata = $this->resourceRegistry->get($alias);
        $routeName = $operation->getRouteName();

        Assert::notNull($routeName, sprintf(
            'Operation %s of %s has no route name. Please define one.',
            $operation::class,
            $alias,
        ));

        $route = $this->createRoute($metadata, $resource, $operation);
        $routeCollection->add($routeName, $route);
    }

    private function createRoute(MetadataInterface $metadata, ResourceMetadata $resource, HttpOperation $operation): Route
    {
        return $this->operationRouteFactory->create($metadata, $resource, $operation);
    }
}
