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

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Resource\Reflection\ClassReflection;
use Sylius\Resource\Symfony\Routing\Factory\AttributesOperationRouteFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\RouteCollection;

final class RoutesAttributesLoader implements RouteLoaderInterface
{
    public function __construct(
        private array $mapping,
        private RouteAttributesFactoryInterface $routesAttributesFactory,
        private AttributesOperationRouteFactoryInterface $attributesOperationRouteFactory,
    ) {
    }

    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();
        $paths = $this->mapping['paths'] ?? [];

        foreach (ClassReflection::getResourcesByPaths($paths) as $className) {
            $this->routesAttributesFactory->createRouteForClass($routeCollection, $className);
            $this->attributesOperationRouteFactory->createRouteForClass($routeCollection, $className);
        }

        return $routeCollection;
    }
}
