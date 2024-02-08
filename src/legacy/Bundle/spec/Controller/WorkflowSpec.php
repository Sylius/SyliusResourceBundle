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

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface as ResourceStateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\Workflow;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow as SymfonyWorkflow;

final class WorkflowSpec extends ObjectBehavior
{
    function let(Registry $registry): void
    {
        $this->beConstructedWith($registry);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Workflow::class);
    }

    function it_implements_state_machine_interface(): void
    {
        $this->shouldImplement(ResourceStateMachineInterface::class);
    }

    function it_throws_an_exception_if_transition_is_not_defined_during_can(RequestConfiguration $requestConfiguration, ResourceInterface $resource): void
    {
        $requestConfiguration->hasStateMachine()->willReturn(false);

        $this
            ->shouldThrow(new \InvalidArgumentException('State machine must be configured to apply transition, check your routing.'))
            ->during('can', [$requestConfiguration, $resource])
        ;
    }

    function it_throws_an_exception_if_transition_is_not_defined_during_apply(RequestConfiguration $requestConfiguration, ResourceInterface $resource): void
    {
        $requestConfiguration->hasStateMachine()->willReturn(false);

        $this
            ->shouldThrow(new \InvalidArgumentException('State machine must be configured to apply transition, check your routing.'))
            ->during('apply', [$requestConfiguration, $resource])
        ;
    }

    function it_returns_if_configured_state_machine_can_transition_without_graph_configuration(
        RequestConfiguration $requestConfiguration,
        Registry $registry,
        ResourceInterface $resource,
        SymfonyWorkflow $workflow,
    ): void {
        $requestConfiguration->getStateMachineGraph()->willReturn(null);
        $requestConfiguration->hasStateMachine()->willReturn(true);
        $requestConfiguration->getStateMachineTransition()->willReturn('reject');
        $registry->get($resource, null)->willReturn($workflow);
        $workflow->can($resource, 'reject')->willReturn(true);

        $this->can($requestConfiguration, $resource)->shouldReturn(true);
    }

    function it_returns_if_configured_state_machine_can_transition_with_graph_configuration(
        RequestConfiguration $requestConfiguration,
        Registry $registry,
        ResourceInterface $resource,
        SymfonyWorkflow $workflow,
    ): void {
        $requestConfiguration->getStateMachineGraph()->willReturn('pull_request');
        $requestConfiguration->hasStateMachine()->willReturn(true);
        $requestConfiguration->getStateMachineTransition()->willReturn('reject');
        $registry->get($resource, 'pull_request')->willReturn($workflow);
        $workflow->can($resource, 'reject')->willReturn(true);

        $this->can($requestConfiguration, $resource)->shouldReturn(true);
    }

    function it_applies_configured_state_machine_transition_without_graph_configuration(
        RequestConfiguration $requestConfiguration,
        Registry $registry,
        ResourceInterface $resource,
        SymfonyWorkflow $workflow,
        Marking $marking,
    ): void {
        $requestConfiguration->getStateMachineGraph()->willReturn(null);
        $requestConfiguration->hasStateMachine()->willReturn(true);
        $requestConfiguration->getStateMachineTransition()->willReturn('reject');
        $registry->get($resource, null)->willReturn($workflow);
        $workflow->apply($resource, 'reject')->willReturn($marking);

        $workflow->apply($resource, 'reject')->shouldBeCalled();

        $this->apply($requestConfiguration, $resource);
    }

    function it_applies_configured_state_machine_transition_with_graph_configuration(
        RequestConfiguration $requestConfiguration,
        Registry $registry,
        ResourceInterface $resource,
        SymfonyWorkflow $workflow,
        Marking $marking,
    ): void {
        $requestConfiguration->getStateMachineGraph()->willReturn('pull_request');
        $requestConfiguration->hasStateMachine()->willReturn(true);
        $requestConfiguration->getStateMachineTransition()->willReturn('reject');
        $registry->get($resource, 'pull_request')->willReturn($workflow);
        $workflow->apply($resource, 'reject')->willReturn($marking);

        $registry->get($resource, 'pull_request')->shouldBeCalled();
        $workflow->apply($resource, 'reject')->shouldBeCalled();

        $this->apply($requestConfiguration, $resource);
    }
}
