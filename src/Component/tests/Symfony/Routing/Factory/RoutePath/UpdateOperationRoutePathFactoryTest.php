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

namespace Sylius\Tests\Resource\Symfony\Routing\Factory\RoutePath;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Api;
use Sylius\Resource\Metadata\ResourceMetadata;
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

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(UpdateOperationRoutePathFactory::class, $this->updateOperationRoutePathFactory);
    }

    public function testItGeneratesRoutePathForUpdateOperations(): void
    {
        $operation = new Update();

        $this->assertSame('/dummies/{id}/edit', $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies'));
    }

    public function testItGeneratesRoutePathForUpdateOperationsWithCustomIdentifier(): void
    {
        $operation = (new Update())->withResource(new ResourceMetadata(identifier: 'code'));

        $this->assertSame('/dummies/{code}/edit', $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies'));
    }

    public function testItGeneratesRoutePathForUpdateOperationsWithCustomShortName(): void
    {
        $operation = new Update(shortName: 'edition');

        $this->assertSame('/dummies/{id}/edition', $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies'));
    }

    public function testItGeneratesRoutePathForApiPutOperations(): void
    {
        $operation = new Api\Put();

        $this->assertSame('/dummies/{id}', $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies'));
    }

    public function testItGeneratesRoutePathForApiPatchOperations(): void
    {
        $operation = new Api\Patch();

        $this->assertSame('/dummies/{id}', $this->updateOperationRoutePathFactory->createRoutePath($operation, '/dummies'));
    }
}
