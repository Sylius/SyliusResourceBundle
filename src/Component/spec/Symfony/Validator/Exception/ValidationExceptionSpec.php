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

namespace spec\Sylius\Component\Resource\Symfony\Validator\Exception;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;

final class ValidationExceptionSpec extends ObjectBehavior
{
    function let(
        ConstraintViolationInterface $firstViolation,
        ConstraintViolationInterface $secondViolation,
    ): void {
        $this->beConstructedWith(new ConstraintViolationList([
            $firstViolation->getWrappedObject(),
            $secondViolation->getWrappedObject(),
        ]));
    }

    function it_transforms_exception_into_a_string(
        ConstraintViolationInterface $firstViolation,
        ConstraintViolationInterface $secondViolation,
    ): void {
        $firstViolation->getPropertyPath()->willReturn('name');
        $firstViolation->getMessage()->willReturn('This value should not be blank.');

        $secondViolation->getPropertyPath()->willReturn('email');
        $secondViolation->getMessage()->willReturn('This value should not be blank.');

        $this->__toString()->shouldReturn("name: This value should not be blank.\nemail: This value should not be blank.");
    }

    function it_can_be_constructed_with_a_message(): void
    {
        $this->beConstructedWith(new ConstraintViolationList([]), 'You should not pass!');

        $this->getMessage()->shouldReturn('You should not pass!');
    }

    function it_can_be_constructed_with_a_code(): void
    {
        $this->beConstructedWith(new ConstraintViolationList([]), '', 42);

        $this->getCode()->shouldReturn(42);
    }

    function it_can_be_constructed_with_a_previous_exception(\Exception $previous): void
    {
        $this->beConstructedWith(new ConstraintViolationList([]), '', 0, $previous->getWrappedObject());

        $this->getPrevious()->shouldReturn($previous);
    }
}
