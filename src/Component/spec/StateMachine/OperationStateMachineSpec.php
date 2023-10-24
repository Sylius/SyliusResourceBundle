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

namespace spec\Sylius\Component\Resource\StateMachine;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Component\Resource\StateMachine\OperationStateMachine;
use Sylius\Component\Resource\StateMachine\OperationStateMachineInterface;
use Sylius\Resource\Context\Context;

final class OperationStateMachineSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator): void
    {
        $this->beConstructedWith($locator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationStateMachine::class);
    }

    function it_calls_can_method_from_operation_state_machine_as_string(
        ContainerInterface $locator,
        OperationStateMachineInterface $stateMachine,
        \stdClass $data,
    ): void {
        $operation = (new Create())->withStateMachineComponent('symfony');
        $context = new Context();

        $locator->has('symfony')->willReturn(true);
        $locator->get('symfony')->willReturn($stateMachine);

        $stateMachine->can($data, $operation, $context)->willReturn(true)->shouldBeCalled();

        $this->can($data, $operation, $context)->shouldReturn(true);
    }

    function it_returns_false_if_no_operation_state_machine_has_been_configured_on_operation(
        ContainerInterface $locator,
        OperationStateMachineInterface $stateMachine,
        \stdClass $data,
    ): void {
        $operation = new Create();
        $context = new Context();

        $locator->has('\App\StateMachine')->willReturn(false);

        $this->can($data, $operation, $context)->shouldReturn(false);
    }

    function it_calls_apply_method_from_operation_state_machine_as_string(
        ContainerInterface $locator,
        OperationStateMachineInterface $stateMachine,
        \stdClass $data,
    ): void {
        $operation = (new Create())->withStateMachineComponent('symfony');
        $context = new Context();

        $locator->has('symfony')->willReturn(true);
        $locator->get('symfony')->willReturn($stateMachine);

        $stateMachine->apply($data, $operation, $context)->shouldBeCalled();

        $this->apply($data, $operation, $context);
    }

    function it_does_nothing_if_no_operation_state_machine_has_been_configured_on_operation(
        ContainerInterface $locator,
        \stdClass $data,
    ): void {
        $operation = new Create();
        $context = new Context();

        $this->apply($data, $operation, $context);
    }

    function it_throws_an_exception_when_operation_does_not_implement_a_state_machine(
        \stdClass $data,
    ): void {
        $operation = new Index();

        $this->shouldThrow(
            new \LogicException(sprintf('Expected an instance of %s. Got: %s', StateMachineAwareOperationInterface::class, Index::class)),
        )->during('can', [$data, $operation, new Context()]);
    }
}
