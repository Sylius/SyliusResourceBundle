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

namespace spec\Sylius\Resource\Context;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Tests\Dummy\DummyClassOne;
use Sylius\Component\Resource\Tests\Dummy\DummyClassTwo;
use Sylius\Resource\Context\Context;

final class ContextSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Context::class);
    }

    function it_is_an_iterator(): void
    {
        $this->shouldImplement(\IteratorAggregate::class);
    }

    function it_can_be_constructed_with_options(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $this->beConstructedWith($optionOne, $optionTwo);

        $this->get(DummyClassOne::class)->shouldReturn($optionOne);
        $this->get(DummyClassTwo::class)->shouldReturn($optionTwo);
    }

    function it_can_be_with_options(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $self = $this->with($optionOne, $optionTwo);

        $self->get(DummyClassOne::class)->shouldReturn($optionOne);
        $self->get(DummyClassTwo::class)->shouldReturn($optionTwo);
    }

    function it_can_be_without_options(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $self = $this->with($optionOne, $optionTwo);
        $self = $self->without(DummyClassOne::class, DummyClassTwo::class);

        $self->get(DummyClassOne::class)->shouldReturn(null);
        $self->get(DummyClassTwo::class)->shouldReturn(null);
    }

    function it_can_be_iterated(): void
    {
        $optionOne = new DummyClassOne();
        $optionTwo = new DummyClassTwo();

        $this->beConstructedWith($optionOne, $optionTwo);

        $this->getIterator()->shouldHaveKey(0);
        $this->getIterator()->shouldHaveKey(1);

        $this->getIterator()->current()->shouldReturn($optionOne);
    }
}
