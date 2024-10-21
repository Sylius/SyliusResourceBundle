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
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\ShowOperationRoutePathFactory;

final class ShowOperationRoutePathFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private ShowOperationRoutePathFactory $showOperationRoutePathFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->showOperationRoutePathFactory = new ShowOperationRoutePathFactory($this->routePathFactory);
    }

    public function testItGeneratesRoutePathForShowOperations(): void
    {
        $operation = new Show();

        $result = $this->showOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}', $result);
    }

    public function testItGeneratesRoutePathForShowOperationsWithCustomIdentifier(): void
    {
        $operation = (new Show())->withResource(new ResourceMetadata(identifier: 'code'));

        $result = $this->showOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{code}', $result);
    }

    public function testItGeneratesRoutePathForShowOperationsWithCustomShortName(): void
    {
        $operation = new Show(shortName: 'details');

        $result = $this->showOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}/details', $result);
    }

    public function testItGeneratesRoutePathForApiGetOperations(): void
    {
        $operation = new Api\Get();

        $result = $this->showOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/{id}', $result);
    }
}
