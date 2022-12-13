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
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class OperationAttributesRouteFactory implements OperationAttributesRouteFactoryInterface
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
        foreach ($resource->getOperations() as $operation) {
            $this->addRouteForOperation($routeCollection, $resource, $operation);
        }
    }

    private function addRouteForOperation(RouteCollection $routeCollection, ResourceMetadata $resource, Operation $operation): void
    {
        Assert::notNull($resource->getAlias(), 'Impossible to get default route name without resource. Please define a resource.');

        $metadata = $this->resourceRegistry->get($resource->getAlias());

        $routeName = $this->getRouteName($metadata, $operation);

        if (!$operation instanceof HttpOperation) {
            return;
        }

        $route = $this->createRoute($metadata, $operation);
        $routeCollection->add($routeName, $route);
    }

    private function createRoute(MetadataInterface $metadata, HttpOperation $operation): Route
    {
        return $this->operationRouteFactory->create($metadata, $operation);
    }

    private function getRouteName(MetadataInterface $metadata, Operation $operation): string
    {
        if (null !== $section = $operation->getSection()) {
            $section = '_' . $section;
        }

        return sprintf('%s%s_%s_%s', $metadata->getApplicationName(), $section ?? '', $metadata->getName(), $operation->getName());
    }
}
