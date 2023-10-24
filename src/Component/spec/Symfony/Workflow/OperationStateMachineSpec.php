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

namespace spec\Sylius\Component\Resource\Symfony\Workflow;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Component\Resource\Symfony\Workflow\OperationStateMachine;
use Sylius\Resource\Context\Context;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

final class OperationStateMachineSpec extends ObjectBehavior
{
    function let(Registry $registry): void
    {
        $this->beConstructedWith($registry);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationStateMachine::class);
    }

    function it_returns_if_transition_is_possible(
        \stdClass $data,
        Registry $registry,
        Workflow $workflow,
    ): void {
        $operation = new Create(stateMachineTransition: 'publish');

        $registry->get($data, null)->willReturn($workflow);

        $workflow->can($data, 'publish')->willReturn(true);

        $this->can($data, $operation, new Context())->shouldReturn(true);
    }

    function it_applies_transition(
        \stdClass $data,
        Registry $registry,
        Workflow $workflow,
        Marking $marking,
    ): void {
        $operation = new Create(stateMachineTransition: 'publish');

        $registry->get($data, null)->willReturn($workflow);

        $workflow->apply($data, 'publish')->willReturn($marking)->shouldBeCalled();

        $this->apply($data, $operation, new Context());
    }

    function it_throws_an_exception_when_operation_has_no_defined_transition(
        \stdClass $data,
        Registry $registry,
        Workflow $workflow,
        Marking $marking,
    ): void {
        $operation = new Create(name: 'app_dummy_create');

        $registry->get($data, null)->willReturn($workflow);

        $this->shouldThrow(new \InvalidArgumentException('No State machine transition was found on operation "app_dummy_create".'))
            ->during('can', [$data, $operation, new Context()])
        ;

        $this->shouldThrow(new \InvalidArgumentException('No State machine transition was found on operation "app_dummy_create".'))
            ->during('apply', [$data, $operation, new Context()])
        ;
    }

    function it_throws_an_exception_when_symfony_workflow_is_not_available(
        \stdClass $data,
    ): void {
        $this->beConstructedWith(null);

        $operation = new Create(stateMachineTransition: 'publish');

        $this->shouldThrow(
            new \LogicException('You can not use the "state-machine" if Symfony workflow is not available. Try running "composer require symfony/workflow".'),
        )->during('can', [$data, $operation, new Context()]);
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
