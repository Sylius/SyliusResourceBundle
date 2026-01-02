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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface as ResourceStateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\Workflow;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow as SymfonyWorkflow;

final class WorkflowTest extends TestCase
{
    private MockObject $registryMock;

    private Workflow $workflow;

    protected function setUp(): void
    {
        $this->markAsSkippedIfSymfonyWorkflowIsNotAvailable();

        $this->registryMock = $this->createMock(Registry::class);
        $this->workflow = new Workflow($this->registryMock);
    }

    public function testImplementsStateMachineInterface(): void
    {
        $this->assertInstanceOf(ResourceStateMachineInterface::class, $this->workflow);
    }

    public function testThrowsAnExceptionIfTransitionIsNotDefinedDuringCan(): void
    {
        $requestConfiguration = $this->createRequestConfigurationWithoutStateMachine();
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('State machine must be configured to apply transition, check your routing.');

        $this->workflow->can($requestConfiguration, $resource);
    }

    public function testThrowsAnExceptionIfTransitionIsNotDefinedDuringApply(): void
    {
        $requestConfiguration = $this->createRequestConfigurationWithoutStateMachine();
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('State machine must be configured to apply transition, check your routing.');

        $this->workflow->apply($requestConfiguration, $resource);
    }

    public function testReturnsIfConfiguredStateMachineCanTransitionWithoutGraphConfiguration(): void
    {
        $requestConfiguration = $this->createRequestConfiguration(null, 'reject');
        $resource = $this->createMock(ResourceInterface::class);
        $workflowMock = $this->createWorkflowMock();

        $this->registryMock
            ->expects($this->once())
            ->method('get')
            ->with($resource, null)
            ->willReturn($workflowMock);

        $workflowMock
            ->expects($this->once())
            ->method('can')
            ->with($resource, 'reject')
            ->willReturn(true);

        $this->assertTrue($this->workflow->can($requestConfiguration, $resource));
    }

    public function testReturnsIfConfiguredStateMachineCanTransitionWithGraphConfiguration(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('pull_request', 'reject');
        $resource = $this->createMock(ResourceInterface::class);
        $workflowMock = $this->createWorkflowMock();

        $this->registryMock
            ->expects($this->once())
            ->method('get')
            ->with($resource, 'pull_request')
            ->willReturn($workflowMock);

        $workflowMock
            ->expects($this->once())
            ->method('can')
            ->with($resource, 'reject')
            ->willReturn(true);

        $this->assertTrue($this->workflow->can($requestConfiguration, $resource));
    }

    public function testAppliesConfiguredStateMachineTransitionWithoutGraphConfiguration(): void
    {
        $requestConfiguration = $this->createRequestConfiguration(null, 'reject');
        $resource = $this->createMock(ResourceInterface::class);
        $workflowMock = $this->createWorkflowMock();
        $marking = $this->createMock(Marking::class);

        $this->registryMock
            ->expects($this->once())
            ->method('get')
            ->with($resource, null)
            ->willReturn($workflowMock);

        $workflowMock
            ->expects($this->once())
            ->method('apply')
            ->with($resource, 'reject')
            ->willReturn($marking);

        $this->workflow->apply($requestConfiguration, $resource);
    }

    public function testAppliesConfiguredStateMachineTransitionWithGraphConfiguration(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('pull_request', 'reject');
        $resource = $this->createMock(ResourceInterface::class);
        $workflowMock = $this->createWorkflowMock();
        $marking = $this->createMock(Marking::class);

        $this->registryMock
            ->expects($this->once())
            ->method('get')
            ->with($resource, 'pull_request')
            ->willReturn($workflowMock);

        $workflowMock
            ->expects($this->once())
            ->method('apply')
            ->with($resource, 'reject')
            ->willReturn($marking);

        $this->workflow->apply($requestConfiguration, $resource);
    }

    /**
     * @return MockObject&RequestConfiguration
     */
    private function createRequestConfigurationWithoutStateMachine(): MockObject
    {
        /** @var MockObject&RequestConfiguration $requestConfiguration */
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $requestConfiguration->method('hasStateMachine')->willReturn(false);

        return $requestConfiguration;
    }

    /**
     * @return MockObject&RequestConfiguration
     */
    private function createRequestConfiguration(?string $graph, string $transition): MockObject
    {
        /** @var MockObject&RequestConfiguration $requestConfiguration */
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $requestConfiguration->method('hasStateMachine')->willReturn(true);
        $requestConfiguration->method('getStateMachineGraph')->willReturn($graph);
        $requestConfiguration->method('getStateMachineTransition')->willReturn($transition);

        return $requestConfiguration;
    }

    /**
     * @return MockObject&SymfonyWorkflow
     */
    private function createWorkflowMock(): MockObject
    {
        /** @var MockObject&SymfonyWorkflow $workflow */
        $workflow = $this->createMock(SymfonyWorkflow::class);

        return $workflow;
    }

    private function markAsSkippedIfSymfonyWorkflowIsNotAvailable(): void
    {
        if (!class_exists(Registry::class)) {
            $this->markTestSkipped('Symfony Workflow is not available.');
        }
    }
}
