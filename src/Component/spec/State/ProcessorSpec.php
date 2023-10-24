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

namespace spec\Sylius\Component\Resource\State;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\State\Processor;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Tests\Dummy\ProcessorWithCallable;
use Sylius\Resource\Context\Context;

final class ProcessorSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator): void
    {
        $this->beConstructedWith($locator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Processor::class);
    }

    function it_calls_process_method_from_operation_processor_as_string(
        ContainerInterface $locator,
        ProcessorInterface $processor,
    ): void {
        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $locator->has('\App\Processor')->willReturn(true);
        $locator->get('\App\Processor')->willReturn($processor);

        $processor->process([], $operation, $context)->shouldBeCalled();

        $this->process([], $operation, $context);
    }

    function it_calls_process_method_from_operation_processor_as_callable(): void
    {
        $operation = new Create(processor: [ProcessorWithCallable::class, 'process']);
        $context = new Context();

        $this->process([], $operation, $context)->shouldHaveType(\stdClass::class);
    }

    function it_returns_null_if_operation_has_no_processor(): void
    {
        $operation = new Create();
        $context = new Context();

        $this->process([], $operation, $context)->shouldReturn(null);
    }

    function it_throws_an_exception_when_configured_processor_is_not_a_processor_instance(
        ContainerInterface $locator,
    ): void {
        $operation = new Create(processor: '\stdClass');
        $context = new Context();

        $locator->has('\stdClass')->willReturn(true);
        $locator->get('\stdClass')->willReturn(new \stdClass());

        $this->shouldThrow(new \InvalidArgumentException('Expected an instance of Sylius\Component\Resource\State\ProcessorInterface. Got: stdClass'))
            ->during('process', [[], $operation, $context])
        ;
    }
}
