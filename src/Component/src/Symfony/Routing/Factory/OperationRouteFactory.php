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

namespace Sylius\Resource\Symfony\Routing\Factory;

use Behat\Transliterator\Transliterator;
use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Resource\Exception\RuntimeException;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation\PathSegmentNameGeneratorInterface;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;
use Symfony\Component\Routing\Route;

/**
 * @experimental
 */
final class OperationRouteFactory implements OperationRouteFactoryInterface
{
    public function __construct(
        private OperationRoutePathFactoryInterface $routePathFactory,
        private PathSegmentNameGeneratorInterface $pathSegmentNameGenerator,
        private bool $routingPathBcLayer = true,
    ) {
    }

    public function create(MetadataInterface $metadata, ResourceMetadata $resource, HttpOperation $operation): Route
    {
        $routePath = $operation->getPath() ?? $this->getDefaultRoutePath($metadata, $resource, $operation);

        if (null !== $routePrefix = $operation->getRoutePrefix()) {
            $routePath = sprintf('%s/%s', rtrim($routePrefix, '/'), ltrim($routePath, '/'));
        }

        return new Route(
            path: $routePath,
            defaults: [
                '_controller' => 'sylius.main_controller',
                '_sylius' => $this->getSyliusOptions($resource, $operation),
            ],
            requirements: $operation->getRouteRequirements() ?? [],
            methods: $operation->getMethods() ?? [],
            condition: $operation->getRouteCondition(),
        );
    }

    private function getDefaultRoutePath(MetadataInterface $legacyMetadata, ResourceMetadata $resource, HttpOperation $operation): string
    {
        return $this->getDefaultRoutePathForOperation($legacyMetadata, $resource, $operation);
    }

    private function getDefaultRoutePathForOperation(MetadataInterface $legacyMetadata, ResourceMetadata $resource, HttpOperation $operation): string
    {
        if (null !== $path = $operation->getPath()) {
            return $path;
        }

        return $this->routePathFactory->createRoutePath($operation, $this->getRootPath($legacyMetadata, $resource));
    }

    private function getRootPath(MetadataInterface $legacyMetadata, ResourceMetadata $resource): string
    {
        if ($this->routingPathBcLayer) {
            if (!class_exists(Urlizer::class) || !class_exists(Transliterator::class)) {
                throw new RuntimeException('Cannot use the routing bc-layer when the "behat/transliterator" package is not installed. Try to disable the routing path bc-layer in the Sylius Resource Bundle configuration using "sylius_resource.routing_path_bc_layer: false"');
            }

            return Urlizer::urlize($legacyMetadata->getPluralName());
        }

        return $this->pathSegmentNameGenerator->getSegmentName(
            name: $resource->getPluralName() ?? '',
            pluralize: false,
        );
    }

    private function getSyliusOptions(ResourceMetadata $resource, HttpOperation $operation): array
    {
        $options = ['resource' => $resource->getAlias()];

        if (null !== $section = $resource->getSection()) {
            $options['section'] = $section;
        }

        // For Legacy Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration
        if (null !== $vars = $operation->getVars()) {
            $options['vars'] = $vars;
        }

        return $options;
    }
}
