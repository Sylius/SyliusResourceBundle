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

namespace spec\Sylius\Component\Resource\StateMachine\State;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\StateMachine\OperationStateMachineInterface;
use Sylius\Component\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;
use Sylius\Resource\Context\Context;
use Sylius\Resource\State\ProcessorInterface;

final class ApplyStateMachineTransitionProcessorSpec extends ObjectBehavior
{
    function let(
        OperationStateMachineInterface $operationStateMachine,
        ProcessorInterface $writeProcessor,
    ): void {
        $this->beConstructedWith($operationStateMachine, $writeProcessor);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ApplyStateMachineTransitionProcessor::class);
    }

    function it_applies_state_machine_transition_if_possible(
        \stdClass $data,
        OperationStateMachineInterface $operationStateMachine,
        ProcessorInterface $writeProcessor,
    ): void {
        $operation = new Create();

        $context = new Context();

        $operationStateMachine->can($data, $operation, $context)->willReturn(true)->shouldBeCalled();
        $operationStateMachine->apply($data, $operation, $context)->shouldBeCalled();

        $writeProcessor->process($data, $operation, $context)->willReturn(null)->shouldBeCalled();

        $this->process($data, $operation, $context);
    }

    function it_does_nothing_when_transition_is_not_possible(
        \stdClass $data,
        OperationStateMachineInterface $operationStateMachine,
        ProcessorInterface $writeProcessor,
    ): void {
        $operation = new Create();

        $context = new Context();

        $operationStateMachine->can($data, $operation, $context)->willReturn(false)->shouldBeCalled();
        $operationStateMachine->apply($data, $operation, $context)->shouldNotBeCalled();

        $writeProcessor->process($data, $operation, $context)->willReturn(null)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn(null);
    }
}
