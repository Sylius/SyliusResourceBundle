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
use Sylius\Bundle\ResourceBundle\Controller\StateMachine;
use SM\Factory\FactoryInterface;
use SM\StateMachine\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface as ResourceStateMachineInterface;
use Sylius\Resource\Model\ResourceInterface;

final class StateMachineTest extends TestCase
{
    /**
     * @var FactoryInterface|MockObject
     */
    private MockObject $stateMachineFactoryMock;
    private StateMachine $stateMachine;
    protected function setUp(): void
    {
        $this->stateMachineFactoryMock = $this->createMock(FactoryInterface::class);
        $this->stateMachine = new StateMachine($this->stateMachineFactoryMock);
    }

    function testImplementsStateMachineInterface(): void
    {
        $this->assertInstanceOf(ResourceStateMachineInterface::class, $this->stateMachine);
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
        $this->stateMachine->can($requestConfigurationMock, $resourceMock);
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
        $this->stateMachine->apply($requestConfigurationMock, $resourceMock);
    }

    function testReturnsIfConfiguredStateMachineCanTransition(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var \SM\StateMachine\StateMachineInterface|MockObject $stateMachineMock */
        $stateMachineMock = $this->createMock(StateMachineInterface::class);
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineGraph')->willReturn('sylius_product_review_state');
        $requestConfigurationMock->expects($this->once())->method('getStateMachineTransition')->willReturn('reject');
        $this->stateMachineFactoryMock->expects($this->once())->method('get')->with($resourceMock, 'sylius_product_review_state')->willReturn($stateMachineMock);
        $stateMachineMock->expects($this->once())->method('can')->with('reject')->willReturn(true);
        $this->assertTrue($this->stateMachine->can($requestConfigurationMock, $resourceMock));
    }

    function testAppliesConfiguredStateMachineTransitionWithoutGraphConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var \SM\StateMachine\StateMachineInterface|MockObject $stateMachineMock */
        $stateMachineMock = $this->createMock(StateMachineInterface::class);
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineGraph')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineTransition')->willReturn('reject');
        $this->stateMachineFactoryMock->expects($this->once())->method('get')->with($resourceMock, 'default')->willReturn($stateMachineMock);
        $this->stateMachineFactoryMock->expects($this->once())->method('get')->with($resourceMock, 'default');
        $stateMachineMock->expects($this->once())->method('apply')->with('reject');
        $this->stateMachine->apply($requestConfigurationMock, $resourceMock);
    }

    function testAppliesConfiguredStateMachineTransitionWithGraphConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var \SM\StateMachine\StateMachineInterface|MockObject $stateMachineMock */
        $stateMachineMock = $this->createMock(StateMachineInterface::class);
        $requestConfigurationMock->expects($this->once())->method('hasStateMachine')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getStateMachineGraph')->willReturn('sylius_product_review_state');
        $requestConfigurationMock->expects($this->once())->method('getStateMachineTransition')->willReturn('reject');
        $this->stateMachineFactoryMock->expects($this->once())->method('get')->with($resourceMock, 'sylius_product_review_state')->willReturn($stateMachineMock);
        $stateMachineMock->expects($this->once())->method('apply')->with('reject');
        $this->stateMachine->apply($requestConfigurationMock, $resourceMock);
    }
}
