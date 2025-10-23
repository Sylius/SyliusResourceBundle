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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface as ResourceStateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\Workflow;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;

final class WorkflowTest extends TestCase
{
    /**
     * @var Registry|MockObject
     */
    private MockObject $registryMock;
    private Workflow $workflow;
    protected function setUp(): void
    {
        $this->registryMock = $this->createMock(Registry::class);
        $this->workflow = new Workflow($this->registryMock);
    }

    function testImplementsStateMachineInterface(): void
    {
        $this->assertInstanceOf(ResourceStateMachineInterface::class, $this->workflow);
    }

    function testThrowsAnExceptionIfTransitionIsNotDefinedDuringCan(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('State machine must be configured to apply transition, check your routing.');
        $this->workflow->can($requestConfigurationMock, $resourceMock);
    }

    function testThrowsAnExceptionIfTransitionIsNotDefinedDuringApply(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('State machine must be configured to apply transition, check your routing.');
        $this->workflow->apply($requestConfigurationMock, $resourceMock);
    }

    function testReturnsIfConfiguredStateMachineCanTransitionWithoutGraphConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var \Symfony\Component\Workflow\Workflow|MockObject $workflowMock */
        $workflowMock = $this->createMock(\Symfony\Component\Workflow\Workflow::class);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineGraph')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineTransition')->willReturn('reject');
        $this->registryMock->expects($this->once())->method('get')->with($resourceMock, null)->willReturn($workflowMock);
        $workflowMock->expects($this->once())->method('can')->with($resourceMock, 'reject')->willReturn(true);
        $this->assertTrue($this->workflow->can($requestConfigurationMock, $resourceMock));
    }

    function testReturnsIfConfiguredStateMachineCanTransitionWithGraphConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var \Symfony\Component\Workflow\Workflow|MockObject $workflowMock */
        $workflowMock = $this->createMock(\Symfony\Component\Workflow\Workflow::class);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineGraph')->willReturn('pull_request');
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineTransition')->willReturn('reject');
        $this->registryMock->expects($this->once())->method('get')->with($resourceMock, 'pull_request')->willReturn($workflowMock);
        $workflowMock->expects($this->once())->method('can')->with($resourceMock, 'reject')->willReturn(true);
        $this->assertTrue($this->workflow->can($requestConfigurationMock, $resourceMock));
    }

    function testAppliesConfiguredStateMachineTransitionWithoutGraphConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var \Symfony\Component\Workflow\Workflow|MockObject $workflowMock */
        $workflowMock = $this->createMock(\Symfony\Component\Workflow\Workflow::class);
        /** @var Marking|MockObject $markingMock */
        $markingMock = $this->createMock(Marking::class);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineGraph')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineTransition')->willReturn('reject');
        $this->registryMock->expects($this->once())->method('get')->with($resourceMock, null)->willReturn($workflowMock);
        $workflowMock->method('apply')->with($resourceMock, 'reject')->willReturn($markingMock);
        $this->workflow->apply($requestConfigurationMock, $resourceMock);
    }

    function testAppliesConfiguredStateMachineTransitionWithGraphConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var \Symfony\Component\Workflow\Workflow|MockObject $workflowMock */
        $workflowMock = $this->createMock(\Symfony\Component\Workflow\Workflow::class);
        /** @var Marking|MockObject $markingMock */
        $markingMock = $this->createMock(Marking::class);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineGraph')->willReturn('pull_request');
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineTransition')->willReturn('reject');
        $this->registryMock->expects($this->once())->method('get')->with($resourceMock, 'pull_request')->willReturn($workflowMock);
        $workflowMock->method('apply')->with($resourceMock, 'reject')->willReturn($markingMock);
        $workflowMock->expects($this->once())->method('apply')->with($resourceMock, 'reject');
        $this->workflow->apply($requestConfigurationMock, $resourceMock);
    }
}
