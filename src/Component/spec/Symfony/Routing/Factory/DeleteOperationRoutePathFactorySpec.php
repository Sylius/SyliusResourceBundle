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
use Sylius\Component\Resource\Metadata\Api;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\Routing\Factory\DeleteOperationRoutePathFactory;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;

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

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}');
    }

    function it_generates_route_path_for_delete_operations_with_custom_identifier(): void
    {
        $operation = (new Delete())->withResource(new Resource(identifier: 'code'));

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{code}');
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
}
