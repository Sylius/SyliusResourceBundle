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

use App\Entity\Route\ShowBook;
use App\Entity\Route\ShowBookWithCriteria;
use App\Entity\Route\ShowBookWithHost;
use App\Entity\Route\ShowBookWithMethods;
use App\Entity\Route\ShowBookWithOptions;
use App\Entity\Route\ShowBookWithPriority;
use App\Entity\Route\ShowBookWithRepository;
use App\Entity\Route\ShowBookWithRequirements;
use App\Entity\Route\ShowBookWithSchemes;
use App\Entity\Route\ShowBookWithSerializationGroups;
use App\Entity\Route\ShowBookWithSerializationVersion;
use App\Entity\Route\ShowBookWithTemplate;
use App\Entity\Route\ShowBookWithVars;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Routing\RouteAttributesFactory;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCompiler;
use Webmozart\Assert\Assert;

final class RouteAttributesFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(RouteAttributesFactory::class);
    }

    function it_generates_routes_from_resource(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBook::class);

        $route = $routeCollection->get('show_book');

        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getDefaults(), [
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ]);
    }

    function it_generates_routes_from_resource_with_methods(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithMethods::class);

        $route = $routeCollection->get('show_book_with_methods');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getMethods(), ['GET']);
        Assert::eq($route->getDefaults(), [
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [],
        ]);
    }

    function it_generates_routes_from_resource_with_criteria(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithCriteria::class);

        $route = $routeCollection->get('show_book_with_criteria');
        Assert::eq($route->getPath(), '/library/{libraryId}/book/{id}');
        Assert::eq($route->getDefaults(), [
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
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithTemplate::class);

        $route = $routeCollection->get('show_book_with_template');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getDefaults(), [
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'template' => 'book/show.html.twig',
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_repository(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithRepository::class);

        $route = $routeCollection->get('show_book_with_repository');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getDefaults(), [
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
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithSerializationGroups::class);

        $route = $routeCollection->get('show_book_with_serialization_groups');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getDefaults(), [
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'serialization_groups' => ['sylius'],
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_serialization_version(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithSerializationVersion::class);

        $route = $routeCollection->get('show_book_with_serialization_version');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getDefaults(), [
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'serialization_version' => '1.0',
            ],
        ]);
    }

    function it_generates_routes_from_resource_with_vars(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithVars::class);

        $route = $routeCollection->get('show_book_with_vars');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getDefaults(), [
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
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithRequirements::class);

        $route = $routeCollection->get('show_book_with_requirements');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getRequirements(), [
            'id' => '\d+',
        ]);
    }

    function it_generates_routes_from_resource_with_priority(): void
    {
        if (Kernel::MAJOR_VERSION < 5) {
            return;
        }

        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithPriority::class);

        Assert::eq($routeCollection->count(), 2);
        $route = $routeCollection->get('show_book_with_priority');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($routeCollection->getIterator()->current(), $route);
    }

    function it_generates_routes_from_resource_with_options(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithOptions::class);

        $route = $routeCollection->get('show_book_with_options');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getOptions(), [
            'compiler_class' => RouteCompiler::class,
            'utf8' => true,
        ]);
    }

    function it_generates_routes_from_resource_with_host(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithHost::class);

        $route = $routeCollection->get('show_book_with_host');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getHost(), 'm.example.com');
    }

    function it_generates_routes_from_resource_with_schemes(): void
    {
        $routeCollection = new RouteCollection();

        $this->createRouteForClass($routeCollection, ShowBookWithSchemes::class);

        $route = $routeCollection->get('show_book_with_schemes');
        Assert::eq($route->getPath(), '/book/{id}');
        Assert::eq($route->getSchemes(), ['https']);
    }
}
