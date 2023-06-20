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

namespace spec\Sylius\Component\Resource\Symfony\Routing\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Api;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRoutePathFactoryInterface;
use Sylius\Component\Resource\Symfony\Routing\Factory\UpdateOperationRoutePathFactory;

final class UpdateOperationRoutePathFactorySpec extends ObjectBehavior
{
    function let(OperationRoutePathFactoryInterface $routePathFactory): void
    {
        $this->beConstructedWith($routePathFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(UpdateOperationRoutePathFactory::class);
    }

    function it_generates_route_path_for_update_operations(): void
    {
        $operation = new Update();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}/edit');
    }

    function it_generates_route_path_for_update_operations_with_custom_identifier(): void
    {
        $operation = (new Update())->withResource(new Resource(identifier: 'code'));

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{code}/edit');
    }

    function it_generates_route_path_for_update_operations_with_custom_short_name(): void
    {
        $operation = new Update(shortName: 'edition');

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}/edition');
    }

    function it_generates_route_path_for_api_put_operations(): void
    {
        $operation = new Api\Put();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}');
    }

    function it_generates_route_path_for_api_patch_operations(): void
    {
        $operation = new Api\Patch();

        $this->createRoutePath($operation, '/dummies')->shouldReturn('/dummies/{id}');
    }
}
