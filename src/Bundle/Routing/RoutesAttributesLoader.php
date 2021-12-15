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

use Sylius\Component\Resource\Annotation\SyliusRoute;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class RoutesAttributesLoader
{
    private array $mapping;
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        /** @var \ReflectionClass $reflectionClass */
        foreach ($this->getReflectionClasses() as $reflectionClass) {
            $this->addRoutesForSyliusRouteAttributes($routeCollection, $reflectionClass);
        }

        return $routeCollection;
    }

    private function addRoutesForSyliusRouteAttributes(RouteCollection $routeCollection, \ReflectionClass $reflectionClass): void
    {
        foreach ($this->getClassAttributes($reflectionClass, SyliusRoute::class) as $reflectionAttribute) {
            $arguments = $reflectionAttribute->getArguments();

            Assert::keyExists($arguments, 'name', 'Your route should have a name attribute.');

            $syliusOptions = [];

            if (isset($arguments['template'])) {
                $syliusOptions['template'] = $arguments['template'];
            }

            if (isset($arguments['vars'])) {
                $syliusOptions['vars'] = $arguments['vars'];
            }

            if (isset($arguments['criteria'])) {
                $syliusOptions['criteria'] = $arguments['criteria'];
            }

            if (isset($arguments['repository'])) {
                $syliusOptions['repository'] = $arguments['repository'];
            }

            if (isset($arguments['serializationGroups'])) {
                $syliusOptions['serialization_groups'] = $arguments['serializationGroups'];
            }

            if (isset($arguments['serializationVersion'])) {
                $syliusOptions['serialization_version'] = $arguments['serializationVersion'];
            }

            $route = new Route(
                $arguments['path'],
                [
                    '_controller' => $arguments['controller'] ?? null,
                    '_sylius' => $syliusOptions,
                ],
                $arguments['requirements'] ?? [],
                $arguments['options'] ?? [],
                $arguments['host'] ?? '',
                $arguments['schemes'] ?? [],
                $arguments['methods'] ?? []
            );

            $routeCollection->add($arguments['name'], $route, $arguments['priority'] ?? 0);
        }
    }

    /**
     * @return \ReflectionAttribute[]
     */
    private function getClassAttributes(\ReflectionClass $reflectionClass, string $attributeName): array
    {
        return $reflectionClass->getAttributes($attributeName);
    }

    private function getReflectionClasses(): iterable
    {
        $paths = $this->mapping['paths'] ?? [];

        foreach ($paths as $resourceDirectory) {
            $resources = ClassReflection::getResourcesByPath($resourceDirectory);

            foreach ($resources as $className) {
                $reflectionClass = new \ReflectionClass($className);

                yield $className => $reflectionClass;
            }
        }
    }
}
