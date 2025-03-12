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

use Sylius\Resource\Metadata\Resource\Factory\ResourceClassListFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\Resource\ResourceRouteCollectionFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @experimental
 */
final class ResourceLoader implements RouteLoaderInterface
{
    public function __construct(
        private readonly ResourceClassListFactoryInterface $resourceClassListFactory,
        private readonly ResourceRouteCollectionFactoryInterface $resourceRouteCollectionFactory,
    ) {
    }

    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();
        $resourceClasses = $this->resourceClassListFactory->create();

        /** @var class-string $class */
        foreach ($resourceClasses as $class) {
            $routeCollection->addCollection($this->resourceRouteCollectionFactory->createRouteCollectionForClass($class));
        }

        return $routeCollection;
    }
}
