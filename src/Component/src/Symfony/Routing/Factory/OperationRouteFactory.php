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
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;
use Symfony\Component\Routing\Route;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class OperationRouteFactory implements OperationRouteFactoryInterface
{
    public function __construct(
        private OperationRoutePathFactoryInterface $routePathFactory,
    ) {
    }

    public function create(ResourceMetadata $resource, HttpOperation $operation): Route
    {
        $routePath = $operation->getPath() ?? $this->getDefaultRoutePath($resource, $operation);

        if (null !== $routePrefix = $operation->getRoutePrefix()) {
            $routePath = $routePrefix . '/' . $routePath;
        }

        return new Route(
            path: $routePath,
            defaults: [
                '_controller' => 'sylius.main_controller',
                '_sylius' => $this->getSyliusOptions($resource, $operation),
            ],
            methods: $operation->getMethods() ?? [],
        );
    }

    private function getDefaultRoutePath(ResourceMetadata $resource, HttpOperation $operation): string
    {
        return $this->getDefaultRoutePathForOperation($resource, $operation);
    }

    private function getDefaultRoutePathForOperation(ResourceMetadata $resource, HttpOperation $operation): string
    {
        $pluralName = $resource->getPluralName();
        Assert::notNull($pluralName, 'Plural name of the resource should be defined');

        $rootPath = sprintf('%s', Urlizer::urlize($pluralName));

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
