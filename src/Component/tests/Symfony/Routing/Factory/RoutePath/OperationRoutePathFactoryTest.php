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
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\Routing\Factory\RoutePath\OperationRoutePathFactory;

final class OperationRoutePathFactoryTest extends TestCase
{
    private OperationRoutePathFactory $operationRoutePathFactory;

    protected function setUp(): void
    {
        $this->operationRoutePathFactory = new OperationRoutePathFactory();
    }

    public function testItThrowsExceptionWhenCalledWithOperationWithName(): void
    {
        $operation = new Show(name: 'app_dummy_show');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Impossible to get a default route path for operation "app_dummy_show". Please define a path.');

        $this->operationRoutePathFactory->createRoutePath($operation, '/dummies');
    }

    public function testItThrowsExceptionWhenCalledWithOperationWithoutName(): void
    {
        $operation = new Show();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Impossible to get a default route path for operation "". Please define a path.');

        $this->operationRoutePathFactory->createRoutePath($operation, '/dummies');
    }
}
