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
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\CreateOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class CreateOperationRoutePathFactorySpec extends ObjectBehavior
{
    function let(OperationRoutePathFactoryInterface $routePathFactory): void
    {
        $this->beConstructedWith($routePathFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CreateOperationRoutePathFactory::class);
    }

    function it_generates_route_path_for_create_operations(): void
    {
        $operation = new Create();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/new');
    }

    function it_generates_route_path_for_create_operations_with_custom_short_name(): void
    {
        $operation = new Create(shortName: 'register');

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/register');
    }

    function it_generates_route_path_for_api_post_operations(): void
    {
        $operation = new Api\Post();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies');
    }
}
