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

namespace Sylius\Resource\Tests\StateMachine;

use LogicException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\StateMachineAwareOperationInterface;
use Sylius\Resource\StateMachine\OperationStateMachine;
use Sylius\Resource\StateMachine\OperationStateMachineInterface;

final class OperationStateMachineTest extends TestCase
{
    private ContainerInterface $locator;

    private OperationStateMachine $operationStateMachine;

    protected function setUp(): void
    {
        $this->locator = $this->createMock(ContainerInterface::class);
        $this->operationStateMachine = new OperationStateMachine($this->locator);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(OperationStateMachine::class, $this->operationStateMachine);
    }

    public function testItCallsCanMethodFromOperationStateMachineAsString(): void
    {
        $stateMachine = $this->createMock(OperationStateMachineInterface::class);
        $data = new \stdClass();
        $operation = (new Create())->withStateMachineComponent('symfony');
        $context = new Context();

        $this->locator->expects($this->once())
            ->method('has')
            ->with('symfony')
            ->willReturn(true);

        $this->locator->expects($this->once())
            ->method('get')
            ->with('symfony')
            ->willReturn($stateMachine);

        $stateMachine->expects($this->once())
            ->method('can')
            ->with($data, $operation, $context)
            ->willReturn(true);

        $this->assertTrue($this->operationStateMachine->can($data, $operation, $context));
    }

    public function testItReturnsFalseIfNoOperationStateMachineHasBeenConfiguredOnOperation(): void
    {
        $data = new \stdClass();
        $operation = new Create();
        $context = new Context();

        $this->assertFalse($this->operationStateMachine->can($data, $operation, $context));
    }

    public function testItCallsApplyMethodFromOperationStateMachineAsString(): void
    {
        $stateMachine = $this->createMock(OperationStateMachineInterface::class);
        $data = new \stdClass();
        $operation = (new Create())->withStateMachineComponent('symfony');
        $context = new Context();

        $this->locator->expects($this->once())
            ->method('has')
            ->with('symfony')
            ->willReturn(true);

        $this->locator->expects($this->once())
            ->method('get')
            ->with('symfony')
            ->willReturn($stateMachine);

        $stateMachine->expects($this->once())
            ->method('apply')
            ->with($data, $operation, $context);

        $this->operationStateMachine->apply($data, $operation, $context);
    }

    public function testItDoesNothingIfNoOperationStateMachineHasBeenConfiguredOnOperation(): void
    {
        $data = new \stdClass();
        $operation = new Create();
        $context = new Context();

        $this->operationStateMachine->apply($data, $operation, $context);
        $this->expectNotToPerformAssertions();
    }

    public function testItThrowsAnExceptionWhenOperationDoesNotImplementAStateMachine(): void
    {
        $data = new \stdClass();
        $operation = new Index();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(sprintf('Expected an instance of %s. Got: %s', StateMachineAwareOperationInterface::class, Index::class));

        $this->operationStateMachine->can($data, $operation, new Context());
    }
}
