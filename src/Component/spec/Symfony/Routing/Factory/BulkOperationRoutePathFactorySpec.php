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
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Metadata\BulkUpdate;
use Sylius\Component\Resource\Symfony\Routing\Factory\BulkOperationRoutePathFactory;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;

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
