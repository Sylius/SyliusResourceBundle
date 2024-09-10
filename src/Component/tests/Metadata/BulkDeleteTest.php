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

namespace Sylius\Resource\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\BulkOperationInterface;
use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;

final class BulkDeleteTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $bulkDelete = new BulkDelete();
        $this->assertInstanceOf(BulkDelete::class, $bulkDelete);
    }

    public function testItIsAnOperation(): void
    {
        $bulkDelete = new BulkDelete();
        $this->assertInstanceOf(Operation::class, $bulkDelete);
    }

    public function testItImplementsDeleteOperationInterface(): void
    {
        $bulkDelete = new BulkDelete();
        $this->assertInstanceOf(DeleteOperationInterface::class, $bulkDelete);
    }

    public function testItImplementsBulkOperationInterface(): void
    {
        $bulkDelete = new BulkDelete();
        $this->assertInstanceOf(BulkOperationInterface::class, $bulkDelete);
    }

    public function testItHasNoResourceByDefault(): void
    {
        $bulkDelete = new BulkDelete();
        $this->assertNull($bulkDelete->getResource());
    }

    public function testItCouldHaveAResource(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book');
        $bulkDelete = (new BulkDelete())->withResource($resource);

        $this->assertSame($resource, $bulkDelete->getResource());
    }

    public function testItHasBulkDeleteShortNameByDefault(): void
    {
        $bulkDelete = new BulkDelete();
        $this->assertEquals('bulk_delete', $bulkDelete->getShortName());
    }

    public function testItHasDeleteMethodsByDefault(): void
    {
        $bulkDelete = new BulkDelete();
        $this->assertEquals(['DELETE', 'POST'], $bulkDelete->getMethods());
    }
}
