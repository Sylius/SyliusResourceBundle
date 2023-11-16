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
use Sylius\Resource\Metadata\Api;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\ShowOperationRoutePathFactory;

final class ShowOperationRoutePathFactorySpec extends ObjectBehavior
{
    function let(OperationRoutePathFactoryInterface $routePathFactory): void
    {
        $this->beConstructedWith($routePathFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShowOperationRoutePathFactory::class);
    }

    function it_generates_route_path_for_show_operations(): void
    {
        $operation = new Show();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}');
    }

    function it_generates_route_path_for_delete_operations_with_custom_identifier(): void
    {
        $operation = (new Show())->withResource(new ResourceMetadata(identifier: 'code'));

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{code}');
    }

    function it_generates_route_path_for_show_operations_with_custom_short_name(): void
    {
        $operation = new Show(shortName: 'details');

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}/details');
    }

    function it_generates_route_path_for_api_get_operations(): void
    {
        $operation = new Api\Get();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}');
    }
}
