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

namespace spec\Sylius\Resource\Symfony\Routing\Factory\RoutePath;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Metadata\Api;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\DeleteOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class DeleteOperationRoutePathFactorySpec extends ObjectBehavior
{
    function let(OperationRoutePathFactoryInterface $routePathFactory): void
    {
        $this->beConstructedWith($routePathFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(DeleteOperationRoutePathFactory::class);
    }

    function it_generates_route_path_for_delete_operations(): void
    {
        $operation = new Delete();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}/delete');
    }

    function it_generates_route_path_for_delete_operations_with_custom_identifier(): void
    {
        $operation = (new Delete())->withResource(new ResourceMetadata(identifier: 'code'));

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{code}/delete');
    }

    function it_generates_route_path_for_update_operations_with_custom_short_name(): void
    {
        $operation = new Delete(shortName: 'remove');

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}/remove');
    }

    function it_generates_route_path_for_api_delete_operations(): void
    {
        $operation = new Api\Delete();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}');
    }

    function it_generates_route_path_for_api_delete_operations_with_custom_short_name(): void
    {
        $operation = new Api\Delete(shortName: 'remove');

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}/remove');
    }
}
