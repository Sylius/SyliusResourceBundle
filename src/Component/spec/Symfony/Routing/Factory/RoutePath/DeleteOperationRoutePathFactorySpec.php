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
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\DeleteOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class DeleteOperationRoutePathFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private DeleteOperationRoutePathFactory $deleteOperationRoutePathFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->deleteOperationRoutePathFactory = new DeleteOperationRoutePathFactory($this->routePathFactory);
    }

    public function testItGeneratesRoutePathForDeleteOperations(): void
    {
        $operation = new Delete();

        $result = $this->deleteOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}/delete', $result);
    }

    public function testItGeneratesRoutePathForDeleteOperationsWithCustomIdentifier(): void
    {
        $operation = (new Delete())->withResource(new ResourceMetadata(identifier: 'code'));

        $result = $this->deleteOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{code}/delete', $result);
    }

    public function testItGeneratesRoutePathForUpdateOperationsWithCustomShortName(): void
    {
        $operation = new Delete(shortName: 'remove');

        $result = $this->deleteOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}/remove', $result);
    }

    public function testItGeneratesRoutePathForApiDeleteOperations(): void
    {
        $operation = new Api\Delete();

        $result = $this->deleteOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}', $result);
    }

    public function testItGeneratesRoutePathForApiDeleteOperationsWithCustomShortName(): void
    {
        $operation = new Api\Delete(shortName: 'remove');

        $result = $this->deleteOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}/remove', $result);
    }
}
