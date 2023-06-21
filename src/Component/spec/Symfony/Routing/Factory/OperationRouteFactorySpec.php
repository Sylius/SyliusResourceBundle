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

namespace spec\Sylius\Component\Resource\Symfony\Routing\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Action\PlaceHolderAction;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRouteFactory;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;

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
            new Resource('app.dummy'),
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
            new Resource('app.dummy'),
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
            new Resource('app.dummy'),
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
            new Resource('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{id}/edit');
        $route->getMethods()->shouldReturn(['GET', 'PUT']);
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
            new Resource('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/{id}');
        $route->getMethods()->shouldReturn(['DELETE']);
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
            new Resource('app.dummy'),
            $operation,
        );

        $route->getPath()->shouldReturn('/dummies/bulk_delete');
        $route->getMethods()->shouldReturn(['DELETE']);
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
            new Resource('app.dummy'),
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
            new Resource(alias: 'app.dummy', section: 'admin'),
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
}
