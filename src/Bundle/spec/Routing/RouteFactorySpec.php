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

namespace spec\Sylius\Bundle\ResourceBundle\Routing;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Routing\RouteFactoryInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class RouteFactorySpec extends ObjectBehavior
{
    function it_implements_route_factory_interface(): void
    {
        $this->shouldImplement(RouteFactoryInterface::class);
    }

    function it_creates_empty_route_collection(): void
    {
        $this->createRouteCollection()->shouldHaveType(RouteCollection::class);
    }

    function it_creates_a_new_route(): void
    {
        $defaults = [
            '_controller' => 'sylius.controller.product:showAction',
        ];

        $requirements = [
            'format' => 'xml|json',
        ];

        $expectedRoute = new Route('/products', $defaults, $requirements, [], 'test.com', ['https'], ['GET', 'POST'], 'condition');

        $this->createRoute('/products', $defaults, $requirements, [], 'test.com', ['https'], ['GET', 'POST'], 'condition')->shouldBeLike($expectedRoute);
    }
}
