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

namespace spec\Sylius\Bundle\ResourceBundle\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Routing\AttributesLoader;
use Sylius\Bundle\ResourceBundle\Routing\ResourceLoader;
use Sylius\Bundle\ResourceBundle\Routing\RouteFactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCompiler;

final class AttributesLoaderSpec extends ObjectBehavior
{
    function let(RegistryInterface $resourceRegistry, RouteFactoryInterface $routeFactory): void
    {
        $this->beConstructedWith(
            ['paths' => [__DIR__.'/../../test/src']],
            new ResourceLoader($resourceRegistry->getWrappedObject(), $routeFactory->getWrappedObject())
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributesLoader::class);
    }

    function it_generates_routes_from_resource(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ]);
    }

    function it_generates_routes_from_resource_with_methods(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_methods');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ]);
    }

    function it_generates_routes_from_resource_with_criteria(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_criteria');
        $route->getPath()->shouldReturn('/library/{libraryId}/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'criteria' => [
                    'library' => '$libraryId',
                ],
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_template(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_template');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'template' => 'book/show.html.twig',
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_repository(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_repository');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'repository' => [
                    'method' => 'findOneNewestByAuthor',
                    'arguments' => '[$author]',
                ],
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_serialization_groups(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_serialization_groups');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'serialization_groups' => ['sylius'],
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_serialization_version(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_serialization_version');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'serialization_version' => '1.0',
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_vars(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_vars');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'vars' => [
                    'foo' => 'bar',
                ],
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_requirements(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_requirements');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getRequirements()->shouldReturn([
            'id' => '\d+',
        ]);
    }

    function it_generates_routes_from_resource_with_priority(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_priority');
        $route->getPath()->shouldReturn('/book/{id}');
        $routes->getIterator()->current()->shouldReturn($route);
    }

    function it_generates_routes_from_resource_with_options(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_options');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getOptions()->shouldReturn([
            'compiler_class' => RouteCompiler::class,
            'utf8' => true,
        ]);
    }

    function it_generates_routes_from_resource_with_host(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_host');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getHost()->shouldReturn('m.example.com');
    }

    function it_generates_routes_from_resource_with_schemes(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        if (\PHP_VERSION_ID < 80000) {
            return;
        }

        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_schemes');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getSchemes()->shouldReturn(['https']);
    }
}
