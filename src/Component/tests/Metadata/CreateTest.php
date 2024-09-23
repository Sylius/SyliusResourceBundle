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
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;

final class CreateTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $create = new Create();
        $this->assertInstanceOf(Create::class, $create);
    }

    public function testItIsAnOperation(): void
    {
        $create = new Create();
        $this->assertInstanceOf(Operation::class, $create);
    }

    public function testItImplementsCreateOperationInterface(): void
    {
        $create = new Create();
        $this->assertInstanceOf(CreateOperationInterface::class, $create);
    }

    public function testItHasNoResourceByDefault(): void
    {
        $create = new Create();
        $this->assertNull($create->getResource());
    }

    public function testItCouldHaveAResource(): void
    {
        $resource = new ResourceMetadata('app.book');
        $create = (new Create())->withResource($resource);

        $this->assertSame($resource, $create->getResource());
    }

    public function testItHasCreateShortNameByDefault(): void
    {
        $create = new Create();
        $this->assertSame('create', $create->getShortName());
    }

    public function testItHasGetAndPostMethodsByDefault(): void
    {
        $create = new Create();
        $this->assertSame(['GET', 'POST'], $create->getMethods());
    }
}
