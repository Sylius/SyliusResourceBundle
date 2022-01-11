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

namespace spec\Sylius\Bundle\ResourceBundle\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Routing\RouteAttributesFactoryInterface;
use Sylius\Bundle\ResourceBundle\Routing\RoutesAttributesLoader;
use Symfony\Component\Routing\RouteCollection;

final class RoutesAttributesLoaderSpec extends ObjectBehavior
{
    function let(RouteAttributesFactoryInterface $routeAttributesFactory): void
    {
        $this->beConstructedWith(['paths' => [__DIR__ . '/../../test/src/Entity/Route']], $routeAttributesFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RoutesAttributesLoader::class);
    }

    function it_generates_routes_from_paths(RouteAttributesFactoryInterface $routeAttributesFactory): void
    {
        $routeAttributesFactory->createRouteForClass(Argument::type(RouteCollection::class), Argument::type('string'))->shouldBeCalledTimes(13);

        $this->__invoke();
    }
}
