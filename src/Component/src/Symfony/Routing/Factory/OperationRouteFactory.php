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

use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Resource\Action\PlaceHolderAction;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\ResourceMetadata;
use Symfony\Component\Routing\Route;

final class OperationRouteFactory implements OperationRouteFactoryInterface
{
    public function __construct(
        private OperationRoutePathFactoryInterface $routePathFactory,
    ) {
    }

    public function create(MetadataInterface $metadata, ResourceMetadata $resource, HttpOperation $operation): Route
    {
        $routePath = $operation->getPath() ?? $this->getDefaultRoutePath($metadata, $operation);

        if (null !== $routePrefix = $operation->getRoutePrefix()) {
            $routePath = $routePrefix . '/' . $routePath;
        }

        return new Route(
            path: $routePath,
            defaults: [
                '_controller' => PlaceHolderAction::class,
                '_sylius' => $this->getSyliusOptions($resource, $operation),
            ],
            methods: $operation->getMethods() ?? [],
        );
    }

    private function getDefaultRoutePath(MetadataInterface $metadata, HttpOperation $operation): string
    {
        return $this->getDefaultRoutePathForOperation($metadata, $operation);
    }

    private function getDefaultRoutePathForOperation(MetadataInterface $metadata, HttpOperation $operation): string
    {
        $rootPath = sprintf('%s', Urlizer::urlize($metadata->getPluralName()));

        if (null !== $path = $operation->getPath()) {
            return $path;
        }

        return $this->routePathFactory->createRoutePath($operation, $rootPath);
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
