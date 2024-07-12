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
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\BulkOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class BulkOperationRoutePathFactorySpec extends ObjectBehavior
{
    function let(OperationRoutePathFactoryInterface $routePathFactory): void
    {
        $this->beConstructedWith($routePathFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(BulkOperationRoutePathFactory::class);
    }

    function it_generates_route_path_for_bulk_delete_operations(): void
    {
        $operation = new BulkDelete();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/bulk_delete');
    }

    function it_generates_route_path_for_bulk_update_operations(): void
    {
        $operation = new BulkUpdate();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/bulk_update');
    }
}
