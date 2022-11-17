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
use App\Entity\Operation\CreateBookWithEvent;
use App\Entity\Operation\CreateBookWithInput;
use App\Entity\Operation\CreateBookWithMethods;
use App\Entity\Operation\CreateBookWithName;
use App\Entity\Operation\CreateBookWithoutResponding;
use App\Entity\Operation\CreateBookWithReturningContent;
use App\Entity\Operation\CreateBookWithoutValidation;
use App\Entity\Operation\CreateBookWithoutWriting;
use App\Entity\Operation\CreateBookWithPath;
use App\Entity\Operation\CreateBookWithPathAndRoutePrefix;
use App\Entity\Operation\CreateBookWithRedirect;
use App\Entity\Operation\CreateBookWithRedirectAndParameters;
use App\Entity\Operation\CreateBookWithRoutePrefix;
use App\Entity\Operation\CreateBookWithTemplate;
use App\Entity\Operation\CreateBookWithVars;
use App\Entity\Operation\CreateResourceBook;
use App\Entity\Operation\IndexBookWithGrid;
use App\Entity\Operation\IndexBookWithPersmission;
use App\Entity\Operation\IndexBookWithSection;
use App\Entity\Operation\PublishBook;
use App\Entity\Operation\ShowBookWithoutReading;
use App\Entity\Operation\UpdateBookWithCsrfProtection;
use App\Entity\Operation\UpdateBookWithStateMachine;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Routing\OperationAttributesRouteFactory;
use Sylius\Bundle\ResourceBundle\Routing\OperationRouteFactory;
use Sylius\Component\Resource\Action\PlaceHolderAction;
use Sylius\Component\Resource\Metadata\Factory\AttributesResourceMetadataFactory;
use Sylius\Component\Resource\Metadata\Factory\OperationFactory;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Symfony\State\ApplyStateMachineProcessor;
use Symfony\Component\Routing\RouteCollection;
use Webmozart\Assert\Assert;

final class OperationAttributesRouteFactorySpec extends ObjectBehavior
{
    function let(RegistryInterface $resourceRegistry): void
    {
        $this->beConstructedWith(
            $resourceRegistry,
            new OperationRouteFactory(),
            new AttributesResourceMetadataFactory(new OperationFactory()),
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationAttributesRouteFactory::class);
    }

    function it_generates_create_route(
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
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
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

        $this->createRouteForClass($routeCollection, CreateResourceBook::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_with_name(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithName::class);

        $route = $routeCollection->get('app_book_add');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/add');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'add',
            ],
        ]);
    }

    function it_generates_create_route_with_methods(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithMethods::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_with_path(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithPath::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/create');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_with_route_prefix(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithRoutePrefix::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/admin/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_with_path_and_route_prefix(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithPathAndRoutePrefix::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/admin/books/create');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_with_template(
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
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'template' => 'book/create.html.twig',
                'resource' => 'app.book',
                'operation' => 'create',
            ],
        ]);
    }

    function it_generates_create_route_with_vars(
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
        Assert::eq($route->getMethods(), ['GET', 'POST']);
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

    function it_generates_create_route_with_criteria(
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
        Assert::eq($route->getMethods(), ['GET', 'POST']);
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

    function it_generates_show_route_without_reading(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, ShowBookWithoutReading::class);

        $route = $routeCollection->get('app_book_show');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/{id}');
        Assert::eq($route->getMethods(), ['GET']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'show',
                'read' => false,
            ],
        ]);
    }

    function it_generates_create_route_without_validation(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithoutValidation::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'validate' => false,
            ],
        ]);
    }

    function it_generates_create_route_without_writing(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithoutWriting::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'write' => false,
            ],
        ]);
    }

    function it_generates_create_route_without_responding(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithoutResponding::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'respond' => false,
            ],
        ]);
    }

    function it_generates_index_route_with_section(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, IndexBookWithSection::class);

        $route = $routeCollection->get('app_admin_book_index');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books');
        Assert::eq($route->getMethods(), ['GET']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'index',
                'section' => 'admin',
            ],
        ]);
    }

    function it_generates_index_route_with_permission(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, IndexBookWithPersmission::class);

        $route = $routeCollection->get('app_book_index');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books');
        Assert::eq($route->getMethods(), ['GET']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'index',
                'permission' => true,
            ],
        ]);
    }

    function it_generates_index_route_with_grid(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, IndexBookWithGrid::class);

        $route = $routeCollection->get('app_book_index');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books');
        Assert::eq($route->getMethods(), ['GET']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'index',
                'grid' => 'app_book',
            ],
        ]);
    }

    function it_generates_update_route_with_csrf_protection(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, UpdateBookWithCsrfProtection::class);

        $route = $routeCollection->get('app_book_update');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/{id}/edit');
        Assert::eq($route->getMethods(), ['GET', 'PUT']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'update',
                'csrf_protection' => true,
            ],
        ]);
    }

    function it_generates_update_route_with_state_machine(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, UpdateBookWithStateMachine::class);

        $route = $routeCollection->get('app_book_update');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/{id}/edit');
        Assert::eq($route->getMethods(), ['GET', 'PUT']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'update',
                'state_machine' => [
                    'transition' => 'publish',
                ],
            ],
        ]);
    }

    function it_generates_create_route_with_input(
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
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'input' => Book::class,
            ],
        ]);
    }

    function it_generates_apply_state_machine_transition_route(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, PublishBook::class);

        $route = $routeCollection->get('app_book_publish');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/{id}/publish');
        Assert::eq($route->getMethods(), ['PUT', 'PATCH']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'publish',
                'processor' => ApplyStateMachineProcessor::class,
                'validate' => false,
                'form' => false,
                'state_machine' => [
                    'transition' => 'publish',
                ],
            ],
        ]);
    }

    function it_generates_create_route_with_redirect(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithRedirect::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'redirect' => 'update',
            ],
        ]);
    }

    function it_generates_create_route_with_redirect_and_parameters(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithRedirectAndParameters::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'redirect' => [
                    'route' => 'update',
                    'parameters' => [
                        'id' => 'resource.id',
                        'foo' => 'fighters',
                    ],
                ],
            ],
        ]);
    }

    function it_generates_create_route_with_event(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithEvent::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'event' => 'register',
            ],
        ]);
    }

    function it_generates_create_route_with_returning_content(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
    ): void {
        $routeCollection = new RouteCollection();

        $resourceRegistry->get('app.book')->willReturn($metadata);

        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');
        $metadata->getPluralName()->willReturn('books');

        $this->createRouteForClass($routeCollection, CreateBookWithReturningContent::class);

        $route = $routeCollection->get('app_book_create');
        Assert::notNull($route);
        Assert::eq($route->getPath(), '/books/new');
        Assert::eq($route->getMethods(), ['GET', 'POST']);
        Assert::eq($route->getDefaults(), [
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.book',
                'operation' => 'create',
                'return_content' => true,
            ],
        ]);
    }
}
