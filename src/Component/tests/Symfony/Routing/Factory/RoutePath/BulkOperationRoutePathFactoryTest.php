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
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\BulkOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class BulkOperationRoutePathFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private BulkOperationRoutePathFactory $bulkOperationRoutePathFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->bulkOperationRoutePathFactory = new BulkOperationRoutePathFactory($this->routePathFactory);
    }

    public function testItGeneratesRoutePathForBulkDeleteOperations(): void
    {
        $operation = new BulkDelete();

        $result = $this->bulkOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/bulk_delete', $result);
    }

    public function testItGeneratesRoutePathForBulkUpdateOperations(): void
    {
        $operation = new BulkUpdate();

        $result = $this->bulkOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/bulk_update', $result);
    }
}
