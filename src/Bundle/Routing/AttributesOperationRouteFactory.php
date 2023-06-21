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

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRouteFactoryInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class AttributesOperationRouteFactory implements AttributesOperationRouteFactoryInterface
{
    public function __construct(
        private RegistryInterface $resourceRegistry,
        private OperationRouteFactoryInterface $operationRouteFactory,
        private ResourceMetadataCollectionFactoryInterface $resourceMetadataFactory,
    ) {
    }

    public function createRouteForClass(RouteCollection $routeCollection, string $className): void
    {
        $resourceMetadata = $this->resourceMetadataFactory->create($className);

        /** @var ResourceMetadata $resource */
        foreach ($resourceMetadata->getIterator() as $resource) {
            $this->createRoutesForResource($routeCollection, $resource);
        }
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
        $metadata = $this->resourceRegistry->get($resource->getAlias() ?? '');
        $routeName = $operation->getRouteName();

        Assert::notNull($routeName, sprintf('Operation %s has no route name. Please define one.', $operation::class));

        $route = $this->createRoute($metadata, $resource, $operation);
        $routeCollection->add($routeName, $route);
    }

    private function createRoute(MetadataInterface $metadata, ResourceMetadata $resource, HttpOperation $operation): Route
    {
        return $this->operationRouteFactory->create($metadata, $resource, $operation);
    }
}
