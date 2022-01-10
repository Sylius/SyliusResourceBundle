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
use Sylius\Bundle\ResourceBundle\Routing\RoutesAttributesLoader;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCompiler;

final class RoutesAttributesLoaderSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(['paths' => [__DIR__.'/../../test/src']]);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RoutesAttributesLoader::class);
    }

    function it_generates_routes_from_resource(): void {
        $routes = $this->__invoke();
        $route = $routes->get('show_book');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ]);
    }

    function it_generates_routes_from_resource_with_methods(): void
    {
        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_methods');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ]);
    }

    function it_generates_routes_from_resource_with_criteria(): void
    {
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

    function it_generates_routes_from_resource_with_template(): void
    {
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

    function it_generates_routes_from_resource_with_repository(): void
    {
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

    function it_generates_routes_from_resource_with_serialization_groups(): void
    {
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

    function it_generates_routes_from_resource_with_serialization_version(): void {
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

    function it_generates_routes_from_resource_with_vars(): void {
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

    function it_generates_routes_from_resource_with_requirements(): void
    {
        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_requirements');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getRequirements()->shouldReturn([
            'id' => '\d+',
        ]);
    }

    function it_generates_routes_from_resource_with_priority(): void
    {
        if (Kernel::MAJOR_VERSION < 5) {
            return;
        }

        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_priority');
        $route->getPath()->shouldReturn('/book/{id}');
        $routes->getIterator()->current()->shouldReturn($route);
    }

    function it_generates_routes_from_resource_with_options(): void
    {
        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_options');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getOptions()->shouldReturn([
            'compiler_class' => RouteCompiler::class,
            'utf8' => true,
        ]);
    }

    function it_generates_routes_from_resource_with_host(): void
    {
        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_host');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getHost()->shouldReturn('m.example.com');
    }

    function it_generates_routes_from_resource_with_schemes(): void
    {
        $routes = $this->__invoke();
        $route = $routes->get('show_book_with_schemes');
        $route->getPath()->shouldReturn('/book/{id}');
        $route->getSchemes()->shouldReturn(['https']);
    }
}
