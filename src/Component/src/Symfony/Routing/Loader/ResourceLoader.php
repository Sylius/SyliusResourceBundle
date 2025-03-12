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

namespace Sylius\Resource\Symfony\Routing\Loader;

use Sylius\Resource\Metadata\Resource\Factory\ResourceNameCollectionFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\Resource\ResourceRouteCollectionFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @experimental
 */
final class ResourceLoader implements RouteLoaderInterface
{
    public function __construct(
        private readonly ResourceNameCollectionFactoryInterface $resourceNameCollectionFactory,
        private readonly ResourceRouteCollectionFactoryInterface $resourceRouteCollectionFactory,
    ) {
    }

    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        $resourceNames = $this->resourceNameCollectionFactory->create();

        /**
         * @var class-string $resourceName
         */
        foreach ($resourceNames as $resourceName) {
            $routeCollection->addCollection($this->resourceRouteCollectionFactory->createRouteCollectionForClass($resourceName));
        }

        return $routeCollection;
    }
}
