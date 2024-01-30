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
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Routing\AttributesOperationRouteFactoryInterface;
use Sylius\Bundle\ResourceBundle\Routing\RouteAttributesFactoryInterface;
use Sylius\Bundle\ResourceBundle\Routing\RoutesAttributesLoader;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\RouteCollection;

final class RoutesAttributesLoaderSpec extends ObjectBehavior
{
    function let(
        RouteAttributesFactoryInterface $routeAttributesFactory,
        AttributesOperationRouteFactoryInterface $attributesOperationRouteFactory,
    ): void {
        $this->beConstructedWith(
            ['paths' => [dirname(__DIR__, 4) . '/../tests/Application/src/Entity/Route']],
            $routeAttributesFactory,
            $attributesOperationRouteFactory,
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RoutesAttributesLoader::class);
    }

    function it_is_a_route_loader(): void
    {
        $this->shouldImplement(RouteLoaderInterface::class);
    }

    function it_generates_routes_from_paths(
        RouteAttributesFactoryInterface $routeAttributesFactory,
        AttributesOperationRouteFactoryInterface $attributesOperationRouteFactory,
    ): void {
        $routeAttributesFactory->createRouteForClass(Argument::type(RouteCollection::class), Argument::type('string'))->shouldBeCalledTimes(25);
        $attributesOperationRouteFactory->createRouteForClass(Argument::type(RouteCollection::class), Argument::type('string'))->shouldBeCalledTimes(25);

        $this->__invoke();
    }
}
