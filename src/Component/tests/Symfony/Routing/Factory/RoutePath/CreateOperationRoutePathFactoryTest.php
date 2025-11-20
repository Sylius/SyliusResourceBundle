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
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\CreateOperationRoutePathFactory;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactoryInterface;

final class CreateOperationRoutePathFactoryTest extends TestCase
{
    private OperationRoutePathFactoryInterface $routePathFactory;

    private CreateOperationRoutePathFactory $createOperationRoutePathFactory;

    protected function setUp(): void
    {
        $this->routePathFactory = $this->createMock(OperationRoutePathFactoryInterface::class);
        $this->createOperationRoutePathFactory = new CreateOperationRoutePathFactory($this->routePathFactory);
    }

    public function testItGeneratesRoutePathForCreateOperations(): void
    {
        $operation = new Create();

        $result = $this->createOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/new', $result);
    }

    public function testItGeneratesRoutePathForCreateOperationsWithCustomShortName(): void
    {
        $operation = new Create(shortName: 'register');

        $result = $this->createOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies/register', $result);
    }

    public function testItGeneratesRoutePathForApiPostOperations(): void
    {
        $operation = new Api\Post();

        $result = $this->createOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies', $result);
    }

    public function testItDelegatesToDecoratedFactoryForNonCreateOperations(): void
    {
        $operation = new Index();

        $this->routePathFactory
            ->expects($this->once())
            ->method('createRoutePath')
            ->with($operation, '/dummies')
            ->willReturn('/dummies');

        $result = $this->createOperationRoutePathFactory->createRoutePath($operation, '/dummies');

        $this->assertSame('/dummies', $result);
    }
}
