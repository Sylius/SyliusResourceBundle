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
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Metadata\CollectionOperationInterface;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;

final class IndexTest extends TestCase
{
    use ProphecyTrait;

    private Index $index;

    protected function setUp(): void
    {
        $this->index = new Index();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Index::class, $this->index);
    }

    public function testItIsAnOperation(): void
    {
        $this->assertInstanceOf(Operation::class, $this->index);
    }

    public function testItImplementsCollectionOperationInterface(): void
    {
        $this->assertInstanceOf(CollectionOperationInterface::class, $this->index);
    }

    public function testItHasNoResourceByDefault(): void
    {
        $this->assertNull($this->index->getResource());
    }

    public function testItCouldHaveAResource(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book');

        $this->index = $this->index->withResource($resource);

        $this->assertSame($resource, $this->index->getResource());
    }

    public function testItHasIndexShortNameByDefault(): void
    {
        $this->assertSame('index', $this->index->getShortName());
    }

    public function testItHasGetMethodsByDefault(): void
    {
        $this->assertSame(['GET'], $this->index->getMethods());
    }
}
