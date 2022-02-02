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

use Sylius\Component\Resource\Annotation\SyliusCrudRoutes;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

final class CrudRoutesAttributesLoader implements RouteLoaderInterface
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
        $paths = $this->mapping['paths'] ?? [];

        /** @var string $className */
        foreach (ClassReflection::getResourcesByPaths($paths) as $className) {
            $this->addRoutesForSyliusCrudRoutesAttributes($routeCollection, $className);
        }

        return $routeCollection;
    }

    private function addRoutesForSyliusCrudRoutesAttributes(RouteCollection $routeCollection, string $className): void
    {
        $attributes = ClassReflection::getClassAttributes($className, SyliusCrudRoutes::class);

        foreach ($attributes as $reflectionAttribute) {
            $resource = Yaml::dump($reflectionAttribute->getArguments());
            $resourceRouteCollection = $this->resourceLoader->load($resource);
            $routeCollection->addCollection($resourceRouteCollection);
        }
    }
}
