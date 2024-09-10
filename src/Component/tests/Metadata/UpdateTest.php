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
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Metadata\UpdateOperationInterface;

final class UpdateTest extends TestCase
{
    private Update $update;

    protected function setUp(): void
    {
        $this->update = new Update();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Update::class, $this->update);
    }

    public function testItIsAnOperation(): void
    {
        $this->assertInstanceOf(Operation::class, $this->update);
    }

    public function testItImplementsUpdateOperationInterface(): void
    {
        $this->assertInstanceOf(UpdateOperationInterface::class, $this->update);
    }

    public function testItHasNoResourceByDefault(): void
    {
        $this->assertNull($this->update->getResource());
    }

    public function testItCouldHaveAResource(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book');

        $this->update = $this->update->withResource($resource);
        $this->assertSame($resource, $this->update->getResource());
    }

    public function testItHasUpdateNameByDefault(): void
    {
        $this->assertSame('update', $this->update->getShortName());
    }

    public function testItHasGetAndPutMethodsByDefault(): void
    {
        $this->assertSame(['GET', 'PUT', 'POST'], $this->update->getMethods());
    }
}
