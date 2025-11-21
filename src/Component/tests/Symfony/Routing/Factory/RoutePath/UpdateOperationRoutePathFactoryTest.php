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
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\UpdateOperationRoutePathFactory;

final class UpdateOperationRoutePathFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private UpdateOperationRoutePathFactory $updateOperationRoutePathFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->updateOperationRoutePathFactory = new UpdateOperationRoutePathFactory($this->routePathFactory);
    }

    public function testItGeneratesRoutePathForUpdateOperations(): void
    {
        $operation = new Update();

        $result = $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}/edit', $result);
    }

    public function testItGeneratesRoutePathForUpdateOperationsWithCustomIdentifier(): void
    {
        $operation = (new Update())->withResource(new ResourceMetadata(identifier: 'code'));

        $result = $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{code}/edit', $result);
    }

    public function testItGeneratesRoutePathForUpdateOperationsWithCustomShortName(): void
    {
        $operation = new Update(shortName: 'edition');

        $result = $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}/edition', $result);
    }

    public function testItGeneratesRoutePathForApiPutOperations(): void
    {
        $operation = new Api\Put();

        $result = $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}', $result);
    }

    public function testItGeneratesRoutePathForApiPatchOperations(): void
    {
        $operation = new Api\Patch();

        $result = $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}', $result);
    }

    public function testItDelegatesToDecoratedFactoryForNonUpdateOperations(): void
    {
        $operation = new Show();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, '/dummies')
            ->willReturn('/dummies/{id}');

        $result = $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}', $result);
    }
}
