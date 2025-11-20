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

namespace Sylius\Resource\Tests\Symfony\Routing\Factory\RoutePath;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Api;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\CollectionOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class CollectionOperationRoutePathFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private CollectionOperationRoutePathFactory $collectionOperationRoutePathFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->collectionOperationRoutePathFactory = new CollectionOperationRoutePathFactory($this->routePathFactory);
    }

    public function testItGeneratesRoutePathForIndexOperations(): void
    {
        $operation = new Index();

        $result = $this->collectionOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies', $result);
    }

    public function testItGeneratesRoutePathForIndexOperationsWithCustomShortName(): void
    {
        $operation = new Index(shortName: 'list');

        $result = $this->collectionOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/list', $result);
    }

    public function testItGeneratesRoutePathForApiGetCollectionOperations(): void
    {
        $operation = new Api\GetCollection();

        $result = $this->collectionOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies', $result);
    }

    public function testItDelegatesToDecoratedFactoryForNonCollectionOperations(): void
    {
        $operation = new Show();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, '/dummies')
            ->willReturn('/dummies/{id}');

        $result = $this->collectionOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}', $result);
    }
}
