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

namespace Sylius\Resource\Tests\Symfony\Routing\Factory\RouteName;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;

final class OperationRouteNameFactoryTest extends TestCase
{
    private OperationRouteNameFactory $operationRouteNameFactory;

    protected function setUp(): void
    {
        $this->operationRouteNameFactory = new OperationRouteNameFactory();
    }

    public function testItCreatesRouteNameForOperationWithResource(): void
    {
        $resource = new ResourceMetadata(
            alias: 'app.dummy',
            name: 'dummy',
            applicationName: 'app',
        );

        $operation = (new Index())->withResource($resource);

        $result = $this->operationRouteNameFactory->createRouteName($operation);

        $this->assertSame('app_dummy_index', $result);
    }

    public function testItCreatesRouteNameForOperationWithSection(): void
    {
        $resource = new ResourceMetadata(
            alias: 'app.dummy',
            section: 'admin',
            name: 'dummy',
            applicationName: 'app',
        );

        $operation = (new Index())->withResource($resource);

        $result = $this->operationRouteNameFactory->createRouteName($operation);

        $this->assertSame('app_admin_dummy_index', $result);
    }

    public function testItCreatesRouteNameWithCustomShortName(): void
    {
        $resource = new ResourceMetadata(
            alias: 'app.dummy',
            name: 'dummy',
            applicationName: 'app',
        );

        $operation = (new Show())->withResource($resource);

        $result = $this->operationRouteNameFactory->createRouteName($operation, 'details');

        $this->assertSame('app_dummy_details', $result);
    }

    public function testItThrowsExceptionWhenOperationHasNoResourceWithShortName(): void
    {
        $operation = new Show(shortName: 'custom_show');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No resource was found on the operation "custom_show"');

        $this->operationRouteNameFactory->createRouteName($operation);
    }

    public function testItThrowsExceptionWhenOperationHasNoResourceWithDefaultShortName(): void
    {
        // Show operation has default shortName "show"
        $operation = new Show();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No resource was found on the operation "show"');

        $this->operationRouteNameFactory->createRouteName($operation);
    }
}
