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

namespace Sylius\Resource\Tests\Context;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Tests\Dummy\DummyClassOne;
use Sylius\Component\Resource\Tests\Dummy\DummyClassTwo;
use Sylius\Resource\Context\Context;

final class ContextTest extends TestCase
{
    /** @test */
    public function it_is_an_iterator(): void
    {
        $context = new Context();
        $this->assertInstanceOf(\IteratorAggregate::class, $context);
    }

    /** @test */
    public function it_can_be_constructed_with_options(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $context = new Context($optionOne, $optionTwo);

        $this->assertEquals($optionOne, $context->get(DummyClassOne::class));
        $this->assertEquals($optionTwo, $context->get(DummyClassTwo::class));
    }

    /** @test */
    public function it_can_be_with_options(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $context = new Context();
        $context = $context->with($optionOne, $optionTwo);

        $this->assertEquals($optionOne, $context->get(DummyClassOne::class));
        $this->assertEquals($optionTwo, $context->get(DummyClassTwo::class));
    }

    /** @test */
    public function it_can_be_without_options(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $context = new Context();
        $context = $context->with($optionOne, $optionTwo);
        $context = $context->without(DummyClassOne::class, DummyClassTwo::class);

        $this->assertNull($context->get(DummyClassOne::class));
        $this->assertNull($context->get(DummyClassTwo::class));
    }

    /** @test */
    public function it_can_be_iterated(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $context = new Context($optionOne, $optionTwo);

        $this->assertEquals($optionOne, $context->getIterator()->current());
        $this->assertEquals(2, iterator_count($context->getIterator()));
    }
}
