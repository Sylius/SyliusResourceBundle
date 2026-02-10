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

namespace Sylius\Resource\Tests\Symfony\Routing\Factory\Resource;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithOperations;
use Sylius\Component\Resource\Tests\Dummy\DummyResourceWithRoutePriorities;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Inflector\Inflector;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Registry;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\AttributesResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactory;
use Sylius\Resource\Symfony\Routing\Factory\Resource\ResourceRouteCollectionFactory;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class ResourceRouteCollectionFactoryTest extends TestCase
{
    private ResourceRouteCollectionFactory $factory;

    private RegistryInterface $resourceRegistry;

    private OperationRoutePathFactoryInterface $routePathFactory;

    protected function setUp(): void
    {
        $this->resourceRegistry = new Registry();
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);

        $this->factory = new ResourceRouteCollectionFactory(
            new OperationRouteFactory($this->routePathFactory, new Operation\DashPathSegmentNameGenerator(new Inflector()), false),
            new AttributesResourceMetadataCollectionFactory(
                $this->resourceRegistry,
                new OperationRouteNameFactory(),
            ),
            $this->resourceRegistry,
        );
    }

    public function testItCreatesRoutesWithOperations(): void
    {
        $this->resourceRegistry->add(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
        ]));

        $routeCollection = $this->factory->createRouteCollectionForClass(DummyResourceWithOperations::class);

        $this->assertCount(4, $routeCollection);
        $this->assertNotNull($routeCollection->get('app_dummy_index'), 'Route "app_dummy_index" not found but it should.');
        $this->assertNotNull($routeCollection->get('app_dummy_create'), 'Route "app_dummy_create" not found but it should.');
        $this->assertNotNull($routeCollection->get('app_dummy_update'), 'Route "app_dummy_update" not found but it should.');
        $this->assertNotNull($routeCollection->get('app_dummy_show'), 'Route "app_dummy_show" not found but it should.');
    }

    public function testItCreatesRoutesWithPriorities(): void
    {
        $this->resourceRegistry->add(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
        ]));

        $routeCollection = $this->factory->createRouteCollectionForClass(DummyResourceWithRoutePriorities::class);

        $this->assertCount(4, $routeCollection);

        $routeNames = array_keys(iterator_to_array($routeCollection->getIterator()));
        $this->assertSame([
            'app_dummy_create',
            'app_dummy_update',
            'app_dummy_index',
            'app_dummy_show',
        ], $routeNames);
    }

    public function testItSkipsNonHttpOperations(): void
    {
        $nonHttpOperation = new class() extends Operation {
            public function getShortName(): ?string
            {
                return 'custom';
            }
        };

        $httpOperation = (new Index(name: 'app_dummy_index'))->withRouteName('app_dummy_index');

        $resource = new ResourceMetadata(
            alias: 'app.dummy',
            name: 'dummy',
            pluralName: 'dummies',
            operations: [
                'app_dummy_custom' => $nonHttpOperation,
                'app_dummy_index' => $httpOperation,
            ],
        );

        $resourceCollection = new ResourceMetadataCollection();
        $resourceCollection[] = $resource;

        $resourceMetadataFactory = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $resourceMetadataFactory
            ->method('create')
            ->with(\stdClass::class)
            ->willReturn($resourceCollection);

        $this->resourceRegistry->add(Metadata::fromAliasAndConfiguration('app.dummy', [
            'driver' => 'dummy_driver',
        ]));

        $factory = new ResourceRouteCollectionFactory(
            new OperationRouteFactory($this->routePathFactory, new Operation\DashPathSegmentNameGenerator(new Inflector()), false),
            $resourceMetadataFactory,
            $this->resourceRegistry,
        );

        $routeCollection = $factory->createRouteCollectionForClass(\stdClass::class);

        $this->assertCount(1, $routeCollection);
        $this->assertNotNull($routeCollection->get('app_dummy_index'), 'Route "app_dummy_index" not found but it should.');
        $this->assertNull($routeCollection->get('app_dummy_custom'), 'Non-HTTP operation should not create a route.');
    }
}
