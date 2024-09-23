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
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Update;

final class OperationsTest extends TestCase
{
    use ProphecyTrait;

    private Operations $operations;

    protected function setUp(): void
    {
        $this->operations = new Operations();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Operations::class, $this->operations);
    }

    public function testItIsCountable(): void
    {
        $this->assertInstanceOf(\Countable::class, $this->operations);
    }

    public function testItIsAnIterator(): void
    {
        $this->assertInstanceOf(\IteratorAggregate::class, $this->operations);
    }

    public function testItAddsOperations(): void
    {
        $this->operations->add('create', new Create());

        $this->assertTrue($this->operations->has('create'));
    }

    public function testItRemovesOperations(): void
    {
        $this->operations->add('create', new Create());

        $this->operations->remove('create');

        $this->assertFalse($this->operations->has('create'));
    }

    public function testItMergesOperations(): void
    {
        $this->operations->add('create', new Create());
        $this->operations->add('create', new Create(name: 'new_name'));

        $this->assertCount(1, $this->operations);
        $this->assertTrue($this->operations->has('create'));
        $this->assertSame('new_name', $this->operations->getIterator()->current()->getName());
    }

    public function testItThrowsARuntimeExceptionWhenRemovingNotFoundOperation(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->operations->remove('not_found_operation');
    }

    public function testItReturnsOperationsCount(): void
    {
        $this->operations->add('create', new Create());
        $this->operations->add('update', new Update());

        $this->assertCount(2, $this->operations);
    }
}
