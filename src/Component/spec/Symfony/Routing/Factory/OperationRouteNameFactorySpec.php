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
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRouteNameFactory;

final class OperationRouteNameFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationRouteNameFactory::class);
    }

    function it_create_a_route_name(): void
    {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');
        $operation = (new Create())->withResource($resource);

        $this->createRouteName($operation)->shouldReturn('app_book_create');
    }

    function it_create_a_route_name_with_a_section(): void
    {
        $resource = new Resource(alias: 'app.book', section: 'admin', name: 'book', applicationName: 'app');
        $operation = (new Create())->withResource($resource);

        $this->createRouteName($operation)->shouldReturn('app_admin_book_create');
    }

    function it_throws_an_exception_when_operation_has_no_resource(): void
    {
        $operation = new Create();

        $this->shouldThrow(new \RuntimeException('No resource was found on the operation "create"'))
            ->during('createRouteName', [$operation])
        ;
    }
}
