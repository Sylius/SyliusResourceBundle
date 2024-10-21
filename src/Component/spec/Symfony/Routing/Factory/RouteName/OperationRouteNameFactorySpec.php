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

namespace Tests\Sylius\Resource\Symfony\Routing\Factory\RouteName;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;

final class OperationRouteNameFactoryTest extends TestCase
{
    private OperationRouteNameFactory $operationRouteNameFactory;

    protected function setUp(): void
    {
        $this->operationRouteNameFactory = new OperationRouteNameFactory();
    }

    public function testIsInitializable(): void
    {
        $this->assertInstanceOf(OperationRouteNameFactory::class, $this->operationRouteNameFactory);
    }

    public function testCreateRouteName(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $operation = (new Create())->withResource($resource);

        $this->assertSame('app_book_create', $this->operationRouteNameFactory->createRouteName($operation));
    }

    public function testCreateRouteNameWithASection(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', section: 'admin', name: 'book', applicationName: 'app');
        $operation = (new Create())->withResource($resource);

        $this->assertSame('app_admin_book_create', $this->operationRouteNameFactory->createRouteName($operation));
    }

    public function testThrowsExceptionWhenOperationHasNoResource(): void
    {
        $operation = new Create();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No resource was found on the operation "create"');

        $this->operationRouteNameFactory->createRouteName($operation);
    }
}
