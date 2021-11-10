<?php

/*
 * This file is part of the test-sylius-attributes project.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Component\Resource\Annotation\SyliusCrudRoutes;
use Sylius\Component\Resource\Annotation\SyliusRoute;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

final class AttributesLoader
{
    private string $resourceDirectory;

    private ResourceLoader $resourceLoader;

    public function __construct(
        string $resourceDirectory,
        ResourceLoader $resourceLoader
    ) {
        $this->resourceDirectory = $resourceDirectory;
        $this->resourceLoader = $resourceLoader;
    }

    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        /** @var \ReflectionClass $reflectionClass */
        foreach ($this->getReflectionClasses() as $reflectionClass) {
            $this->addRoutesForSyliusCrudRoutesAttributes($routeCollection, $reflectionClass);
            $this->addRoutesForSyliusRouteAttributes($routeCollection, $reflectionClass);
        }

        return $routeCollection;
    }

    private function addRoutesForSyliusCrudRoutesAttributes(RouteCollection $routeCollection, \ReflectionClass $reflectionClass): void
    {
        foreach ($this->getClassAttributes($reflectionClass, SyliusCrudRoutes::class) as $reflectionAttribute) {
            $resource = Yaml::dump($reflectionAttribute->getArguments());
            $resourceRouteCollection = $this->resourceLoader->load($resource);
            $routeCollection->addCollection($resourceRouteCollection);
        }
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

    private function getReflectionClasses(): \Iterator
    {
        $resources = $this->getResourcesByPath($this->resourceDirectory);

        foreach ($resources as $className) {
            $reflectionClass = new \ReflectionClass($className);

            yield $className => $reflectionClass;
        }
    }

    private function getResourcesByPath(string $path): array
    {
        $finder = new Finder();
        $finder->files()->in($path)->name('*.php')->sortByName(true);
        $classes = [];

        foreach ($finder as $file) {
            $fileContent = (string) file_get_contents((string) $file->getRealPath());

            preg_match('/namespace (.+);/', $fileContent, $matches);

            $namespace = $matches[1] ?? null;

            if (!preg_match('/class +([^{ ]+)/', $fileContent, $matches)) {
                // no class found
                continue;
            }

            $className = trim($matches[1]);

            if (null !== $namespace) {
                $classes[] = $namespace.'\\'.$className;
            } else {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
