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

use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\RouteCollection;

final class RoutesAttributesLoader
{
    private array $mapping;

    private RouteAttributesFactoryInterface $routesAttributesFactory;

    public function __construct(array $mapping, RouteAttributesFactoryInterface $routesAttributesFactory)
    {
        $this->mapping = $mapping;
        $this->routesAttributesFactory = $routesAttributesFactory;
    }

    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();
        $paths = $this->mapping['paths'] ?? [];

        /** @var string $className */
        foreach (ClassReflection::getResourcesByPaths($paths) as $className) {
            $this->routesAttributesFactory->createRouteForClass($routeCollection, $className);
        }

        return $routeCollection;
    }

    private function getClasses(): iterable
    {
        $paths = $this->mapping['paths'] ?? [];

        foreach ($paths as $resourceDirectory) {
            $resources = ClassReflection::getResourcesByPath($resourceDirectory);

            foreach ($resources as $className) {
                yield $className;
            }
        }
    }
}
