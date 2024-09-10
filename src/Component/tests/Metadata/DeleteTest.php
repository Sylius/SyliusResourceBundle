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
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;

final class DeleteTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $delete = new Delete();
        $this->assertInstanceOf(Delete::class, $delete);
    }

    public function testItIsAnOperation(): void
    {
        $delete = new Delete();
        $this->assertInstanceOf(Operation::class, $delete);
    }

    public function testItImplementsDeleteOperationInterface(): void
    {
        $delete = new Delete();
        $this->assertInstanceOf(DeleteOperationInterface::class, $delete);
    }

    public function testItHasNoResourceByDefault(): void
    {
        $delete = new Delete();
        $this->assertNull($delete->getResource());
    }

    public function testItCouldHaveAResource(): void
    {
        $resource = new ResourceMetadata('app.book');
        $delete = (new Delete())->withResource($resource);

        $this->assertSame($resource, $delete->getResource());
    }

    public function testItHasDeleteShortNameByDefault(): void
    {
        $delete = new Delete();
        $this->assertSame('delete', $delete->getShortName());
    }

    public function testItHasDeleteMethodsByDefault(): void
    {
        $delete = new Delete();
        $this->assertSame(['DELETE', 'POST'], $delete->getMethods());
    }
}
