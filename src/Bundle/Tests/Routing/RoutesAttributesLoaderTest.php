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

namespace Routing;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCompiler;

final class RoutesAttributesLoaderTest extends KernelTestCase
{
    /**
     * @test
     */
    public function it_generates_routes_from_resource(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_methods(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_criteria(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_template(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_repository(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_serialization_groups(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_serialization_version(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_vars(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_requirements(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

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
    public function it_generates_routes_from_resource_with_priority(): void
    {
        if (Kernel::MAJOR_VERSION < 5) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_priority');
        $this->assertNotNull($route);
        $this->assertSame($route, array_values($routesCollection->all())[0]);
    }

    /**
     * @test
     */
    public function it_generates_routes_from_resource_with_options(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_options');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals([
            'compiler_class' => RouteCompiler::class,
            'utf8' => true,
        ], $route->getOptions());
    }

    /**
     * @test
     */
    public function it_generates_routes_from_resource_with_host(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_host');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals('m.example.com', $route->getHost());
    }

    /**
     * @test
     */
    public function it_generates_routes_from_resource_with_schemes(): void
    {
        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.routes_attributes');

        $routesCollection = $attributesLoader->__invoke();

        $route = $routesCollection->get('show_book_with_schemes');
        $this->assertNotNull($route);
        $this->assertEquals('/book/{id}', $route->getPath());
        $this->assertEquals(['https'], $route->getSchemes());
    }
}
