<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Component\Resource\Annotation\SyliusCrudRoutes;
use Sylius\Component\Resource\Annotation\SyliusRoute;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

final class CrudRoutesAttributesLoader
{
    private array $mapping;

    private ResourceLoader $resourceLoader;

    public function __construct(
        array $mapping,
        ResourceLoader $resourceLoader
    ) {
        $this->mapping = $mapping;
        $this->resourceLoader = $resourceLoader;
    }

    public function __invoke(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        /** @var \ReflectionClass $reflectionClass */
        foreach ($this->getReflectionClasses() as $reflectionClass) {
            $this->addRoutesForSyliusCrudRoutesAttributes($routeCollection, $reflectionClass);
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
