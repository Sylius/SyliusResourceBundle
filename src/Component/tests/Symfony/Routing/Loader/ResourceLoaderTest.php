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

namespace Sylius\Resource\Tests\Symfony\Routing\Loader;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Resource\Factory\ResourceClassListFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceClassList;
use Sylius\Resource\Symfony\Routing\Factory\Resource\ResourceRouteCollectionFactoryInterface;
use Sylius\Resource\Symfony\Routing\Loader\ResourceLoader;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class ResourceLoaderTest extends TestCase
{
    private ResourceLoader $loader;

    private ResourceClassListFactoryInterface $resourceClassListFactory;

    private ResourceRouteCollectionFactoryInterface $resourceRouteCollectionFactory;

    protected function setUp(): void
    {
        $this->resourceClassListFactory = $this->createMock(ResourceClassListFactoryInterface::class);
        $this->resourceRouteCollectionFactory = $this->createMock(ResourceRouteCollectionFactoryInterface::class);

        $this->loader = new ResourceLoader(
            $this->resourceClassListFactory,
            $this->resourceRouteCollectionFactory,
        );
    }

    public function testItIsARouteLoader(): void
    {
        $this->assertInstanceOf(RouteLoaderInterface::class, $this->loader);
    }

    public function testItGeneratesRoutesFromResource(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('first_route', new Route('/first-route'));
        $routeCollection->add('second_route', new Route('/second-route'));

        $resourceClassList = new ResourceClassList(['\DummyClass']);

        $this->resourceClassListFactory->method('create')->willReturn($resourceClassList);
        $this->resourceRouteCollectionFactory->method('createRouteCollectionForClass')->with('\DummyClass')->willReturn($routeCollection);

        $this->assertEquals($routeCollection, ($this->loader)());
    }
}
