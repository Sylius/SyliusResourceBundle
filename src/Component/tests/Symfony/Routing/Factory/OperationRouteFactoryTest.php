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

namespace Sylius\Tests\Resource\Symfony\Routing\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\Routing\Factory\OperationRouteFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class OperationRouteFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private OperationRouteFactory $operationRouteFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->operationRouteFactory = new OperationRouteFactory($this->routePathFactory);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(OperationRouteFactory::class, $this->operationRouteFactory);
    }

    public function testItGeneratesCreateRoutes(): void
    {
        $operation = new Create();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('dummies/new');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies/new', $route->getPath());
        $this->assertSame(['GET', 'POST'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesIndexRoutes(): void
    {
        $operation = new Index();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('dummies');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies', $route->getPath());
        $this->assertSame(['GET'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesShowRoutes(): void
    {
        $operation = new Show();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('dummies/{id}');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies/{id}', $route->getPath());
        $this->assertSame(['GET'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesUpdateRoutes(): void
    {
        $operation = new Update();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('dummies/{id}/edit');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies/{id}/edit', $route->getPath());
        $this->assertSame(['GET', 'PUT', 'POST'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesDeleteRoutes(): void
    {
        $operation = new Delete();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('dummies/{id}');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies/{id}', $route->getPath());
        $this->assertSame(['DELETE', 'POST'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesBulkDeleteRoutes(): void
    {
        $operation = new BulkDelete();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('dummies/bulk_delete');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies/bulk_delete', $route->getPath());
        $this->assertSame(['DELETE', 'POST'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesBulkUpdateRoutes(): void
    {
        $operation = new BulkUpdate();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('dummies/bulk_update');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies/bulk_update', $route->getPath());
        $this->assertSame(['PUT', 'PATCH'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesCustomOperationsRoutes(): void
    {
        $operation = new HttpOperation(methods: ['PATCH'], path: 'dummies/{id}/custom');
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->never())
            ->method('createRoutePath');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata('app.dummy'), $operation);

        $this->assertSame('/dummies/{id}/custom', $route->getPath());
        $this->assertSame(['PATCH'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
            ],
        ], $route->getDefaults());
    }

    public function testItGeneratesRoutesWithSections(): void
    {
        $operation = new Show();
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);

        $this->routePathFactory->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, 'dummies')
            ->willReturn('/dummies/{id}');

        $route = $this->operationRouteFactory->create($metadata, new ResourceMetadata(alias: 'app.dummy', section: 'admin'), $operation);

        $this->assertSame('/dummies/{id}', $route->getPath());
        $this->assertSame(['GET'], $route->getMethods());
        $this->assertSame([
            '_controller' => 'sylius.main_controller',
            '_sylius' => [
                'resource' => 'app.dummy',
                'section' => 'admin',
            ],
        ], $route->getDefaults());
    }
}
