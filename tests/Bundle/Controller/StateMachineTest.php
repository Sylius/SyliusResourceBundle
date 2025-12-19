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
use SM\Factory\FactoryInterface;
use SM\StateMachine\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\StateMachine;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface as ResourceStateMachineInterface;
use Sylius\Resource\Model\ResourceInterface;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

final class StateMachineTest extends TestCase
{
    private MockObject $stateMachineFactoryMock;

    private StateMachine $stateMachine;

    protected function setUp(): void
    {
        $this->markAsSkippedIfWinzouStateMachineIsNotAvailable();

        $this->stateMachineFactoryMock = $this->createMock(FactoryInterface::class);
        $this->stateMachine = new StateMachine($this->stateMachineFactoryMock);
    }

    public function testImplementsStateMachineInterface(): void
    {
        $this->assertInstanceOf(ResourceStateMachineInterface::class, $this->stateMachine);
    }

    public function testThrowsAnExceptionIfTransitionIsNotDefinedDuringCan(): void
    {
        $requestConfiguration = $this->createRequestConfigurationWithoutStateMachine();
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('State machine must be configured to apply transition, check your routing.');

        $this->stateMachine->can($requestConfiguration, $resource);
    }

    public function testThrowsAnExceptionIfTransitionIsNotDefinedDuringApply(): void
    {
        $requestConfiguration = $this->createRequestConfigurationWithoutStateMachine();
        $resource = $this->createMock(ResourceInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('State machine must be configured to apply transition, check your routing.');

        $this->stateMachine->apply($requestConfiguration, $resource);
    }

    public function testReturnsIfConfiguredStateMachineCanTransition(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('sylius_product_review_state', 'reject');
        $resource = $this->createMock(ResourceInterface::class);
        $stateMachineMock = $this->createStateMachineMock();

        $this->stateMachineFactoryMock
            ->expects($this->once())
            ->method('get')
            ->with($resource, 'sylius_product_review_state')
            ->willReturn($stateMachineMock);

        $stateMachineMock
            ->expects($this->once())
            ->method('can')
            ->with('reject')
            ->willReturn(true);

        $this->assertTrue($this->stateMachine->can($requestConfiguration, $resource));
    }

    public function testAppliesConfiguredStateMachineTransitionWithoutGraphConfiguration(): void
    {
        $requestConfiguration = $this->createRequestConfiguration(null, 'reject');
        $resource = $this->createMock(ResourceInterface::class);
        $stateMachineMock = $this->createStateMachineMock();

        $this->stateMachineFactoryMock
            ->expects($this->once())
            ->method('get')
            ->with($resource, 'default')
            ->willReturn($stateMachineMock);

        $stateMachineMock
            ->expects($this->once())
            ->method('apply')
            ->with('reject');

        $this->stateMachine->apply($requestConfiguration, $resource);
    }

    public function testAppliesConfiguredStateMachineTransitionWithGraphConfiguration(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('sylius_product_review_state', 'reject');
        $resource = $this->createMock(ResourceInterface::class);
        $stateMachineMock = $this->createStateMachineMock();

        $this->stateMachineFactoryMock
            ->expects($this->once())
            ->method('get')
            ->with($resource, 'sylius_product_review_state')
            ->willReturn($stateMachineMock);

        $stateMachineMock
            ->expects($this->once())
            ->method('apply')
            ->with('reject');

        $this->stateMachine->apply($requestConfiguration, $resource);
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
     * @return MockObject&StateMachineInterface
     */
    private function createStateMachineMock(): MockObject
    {
        /** @var MockObject&StateMachineInterface $stateMachine */
        $stateMachine = $this->createMock(StateMachineInterface::class);

        return $stateMachine;
    }

    private function markAsSkippedIfWinzouStateMachineIsNotAvailable(): void
    {
        if (!class_exists(winzouStateMachineBundle::class)) {
            $this->markTestSkipped('Winzou State machine is not available.');
        }
    }
}
