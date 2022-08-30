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

use App\Dto\Book;
use App\Entity\Operation\CreateBook;
use App\Entity\Operation\CreateBookWithCriteria;
use App\Entity\Operation\CreateBookWithInput;
use App\Entity\Operation\CreateBookWithTemplate;
use App\Entity\Operation\CreateBookWithVars;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Routing\OperationAttributesRouteFactory;
use Sylius\Bundle\ResourceBundle\Routing\OperationRouteFactory;
use Sylius\Component\Resource\Action\PlaceHolderAction;
use Sylius\Component\Resource\Metadata\Factory\OperationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class OperationAttributesRouteFactorySpec extends ObjectBehavior
{
    function let(RegistryInterface $resourceRegistry): void
    {
        $this->beConstructedWith($resourceRegistry, new OperationFactory(), new OperationRouteFactory());
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationAttributesRouteFactory::class);
    }

    function it_generates_create_route_from_resource(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBook::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');

        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_from_resource_with_template(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithTemplate::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'template' => 'book/create.html.twig',
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_from_resource_with_vars(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithVars::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'vars' => [
                    'foo' => 'bar',
                ],
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_from_resource_with_criteria(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithCriteria::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'criteria' => [
                    'library' => '$libraryId',
                ],
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_from_resource_with_input(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithInput::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'input' => Book::class,
            ],
        ]);
    }
}
