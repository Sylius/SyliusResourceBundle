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
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\ShowOperationInterface;

final class ShowTest extends TestCase
{
    private Show $show;

    protected function setUp(): void
    {
        $this->show = new Show();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Show::class, $this->show);
    }

    public function testItIsAnOperation(): void
    {
        $this->assertInstanceOf(Operation::class, $this->show);
    }

    public function testItImplementsShowOperationInterface(): void
    {
        $this->assertInstanceOf(ShowOperationInterface::class, $this->show);
    }

    public function testItHasNoResourceByDefault(): void
    {
        $this->assertNull($this->show->getResource());
    }

    public function testItCouldHaveAResource(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book');

        $this->show = $this->show->withResource($resource);
        $this->assertSame($resource, $this->show->getResource());
    }

    public function testItHasShowShortNameByDefault(): void
    {
        $this->assertSame('show', $this->show->getShortName());
    }

    public function testItHasGetMethodsByDefault(): void
    {
        $this->assertSame(['GET'], $this->show->getMethods());
    }
}
