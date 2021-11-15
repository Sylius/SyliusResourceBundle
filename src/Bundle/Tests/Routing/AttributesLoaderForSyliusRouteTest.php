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

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_template(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_template');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'template' => 'book/show.html.twig',
            ],
        ], $route->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_repository(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_repository');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'repository' => [
                    'method' => 'findOneNewestByAuthor',
                    'arguments' => '[$author]',
                ],
            ],
        ], $route->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_serialization_groups(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_serialization_groups');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'serialization_groups' => ['sylius'],
            ],
        ], $route->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_serialization_version(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_serialization_version');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'serialization_version' => '1.0',
            ],
        ], $route->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_vars(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_vars');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'vars' => [
                    'foo' => 'bar',
                ],
            ],
        ], $route->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_requirements(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_requirements');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            'id' => '\d+',
        ], $route->getRequirements());
    }

    /**
     * @test
     */
    public function it_generates_route_from_resource_with_priority(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_priority');
        $this->assertNotNull($route);
        $this->assertSame($route, array_values($routesCollection->all())[0]);
    }
}
