<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Resource\Symfony\Routing\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Resource\Action\PlaceHolderAction;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactory;
use Sylius\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;

final class OperationRouteFactorySpec extends ObjectBehavior
{
    function let(OperationRoutePathFactoryInterface $routePathFactory): void
    {
        $this->beConstructedWith($routePathFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationRouteFactory::class);
    }

    function it_generates_create_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new Create();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('dummies/new')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/new');
        $route->getMethods()->shouldReturn(['GET', 'POST']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_index_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new Index();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('dummies')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            new Index(),
        );

        $route->getPath()->shouldReturn('/dummies');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_show_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new Show();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('dummies/{id}')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_update_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new Update();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('dummies/{id}/edit')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{id}/edit');
        $route->getMethods()->shouldReturn(['GET', 'PUT', 'POST']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_delete_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new Delete();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('dummies/{id}')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['DELETE', 'POST']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_bulk_delete_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new BulkDelete();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('dummies/bulk_delete')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/bulk_delete');
        $route->getMethods()->shouldReturn(['DELETE', 'POST']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_bulk_update_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new BulkUpdate();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('dummies/bulk_update')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/bulk_update');
        $route->getMethods()->shouldReturn(['PUT', 'PATCH']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_custom_operations_routes(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new HttpOperation(methods: ['PATCH'], path: 'dummies/{id}/custom');

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath(Argument::cetera())->willReturn('')->shouldNotBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{id}/custom');
        $route->getMethods()->shouldReturn(['PATCH']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ]);
    }

    function it_generates_routes_with_sections(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new Show();

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('/dummies/{id}')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata(alias: 'app.dummy', section: 'admin'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['GET']);
        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
                'section' => 'admin',
            ],
        ]);
    }

    function it_generates_routes_with_vars(
        OperationRoutePathFactoryInterface $routePathFactory,
    ): void {
        $operation = new Index(vars: ['subheader' => 'Managing your library']);

        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $routePathFactory->createRoutePath($operation, 'dummies')->willReturn('/dummies')->shouldBeCalled();

        $route = $this->create(
            $metadata,
            new ResourceMetadata(alias: 'app.dummy'),
            $operation,
        );

        $route->getDefaults()->shouldReturn([
            '_controller' => PlaceHolderAction::class,
            '_sylius' => [
                'resource' => 'app.dummy',
                'vars' => ['subheader' => 'Managing your library'],
            ],
        ]);
    }
}
