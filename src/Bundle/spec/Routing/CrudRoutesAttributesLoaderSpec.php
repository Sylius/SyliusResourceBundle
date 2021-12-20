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
use Sylius\Bundle\ResourceBundle\Routing\CrudRoutesAttributesLoader;
use Sylius\Bundle\ResourceBundle\Routing\ResourceLoader;
use Sylius\Bundle\ResourceBundle\Routing\RouteFactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class CrudRoutesAttributesLoaderSpec extends ObjectBehavior
{
    function let(RegistryInterface $resourceRegistry, RouteFactoryInterface $routeFactory): void
    {
        $this->beConstructedWith(
            ['paths' => [__DIR__.'/../../test/src']],
            new ResourceLoader($resourceRegistry->getWrappedObject(), $routeFactory->getWrappedObject())
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CrudRoutesAttributesLoader::class);
    }

    function it_generates_routes_from_resource(
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        RouteFactoryInterface $routeFactory,
        Route $route,
        RouteCollection $routeCollection
    ): void {
        $resourceRegistry->get(Argument::cetera())->willReturn($metadata);
        $metadata->getPluralName()->willReturn('books');
        $metadata->getServiceId('controller')->willReturn('app.controller.book');
        $metadata->getApplicationName()->willReturn('app');
        $metadata->getName()->willReturn('book');

        $routeFactory->createRouteCollection()->willReturn($routeCollection);
        $routeFactory->createRoute(Argument::cetera())->willReturn($route);

        $routeCollection->all()->willReturn([$route->getWrappedObject()]);
        $routeCollection->getResources()->willReturn([]);

        $routeCollection->add(Argument::type('string'), $route)->shouldBeCalled();

        $this->__invoke();
    }
}
