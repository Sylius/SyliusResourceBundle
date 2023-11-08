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
use Sylius\Component\Resource\Symfony\Routing\Factory\CollectionOperationRoutePathFactory;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;
use Sylius\Resource\Metadata\Api;
use Sylius\Resource\Metadata\Index;

final class CollectionOperationRoutePathFactorySpec extends ObjectBehavior
{
    function let(OperationRoutePathFactoryInterface $routePathFactory): void
    {
        $this->beConstructedWith($routePathFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CollectionOperationRoutePathFactory::class);
    }

    function it_generates_route_path_for_index_operations(): void
    {
        $operation = new Index();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies');
    }

    function it_generates_route_path_for_index_operations_with_custom_short_name(): void
    {
        $operation = new Index(shortName: 'list');

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/list');
    }

    function it_generates_route_path_for_api_get_collection_operations(): void
    {
        $operation = new Api\GetCollection();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies');
    }
}
