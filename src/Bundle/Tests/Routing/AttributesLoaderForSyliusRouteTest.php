<?php

declare(strict_types=1);

namespace Routing;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AttributesLoaderForSyliusRouteTest extends KernelTestCase
{
    /**
     * @test
     */
    public function it_generates_route_from_resource(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ], $route->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_methods(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_methods');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ], $route->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_criteria(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_criteria');
        $this->assertNotNull($route);
        $this->assertEquals('/library/{libraryId}/book/{id}', $route->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'criteria' => [
                    'library' => '$libraryId',
                ],
            ],
        ], $route->getDefaults());
    }
}
