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

namespace Sylius\Resource\Tests\StateMachine\State;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\StateMachine\OperationStateMachineInterface;
use Sylius\Resource\StateMachine\State\ApplyStateMachineTransitionProcessor;

final class ApplyStateMachineTransitionProcessorTest extends TestCase
{
    private OperationStateMachineInterface $operationStateMachine;

    private ProcessorInterface $writeProcessor;

    private ApplyStateMachineTransitionProcessor $processor;

    protected function setUp(): void
    {
        $this->operationStateMachine = $this->createMock(OperationStateMachineInterface::class);
        $this->writeProcessor = $this->createMock(ProcessorInterface::class);
        $this->processor = new ApplyStateMachineTransitionProcessor(
            $this->operationStateMachine,
            $this->writeProcessor,
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ApplyStateMachineTransitionProcessor::class, $this->processor);
    }

    public function testItAppliesStateMachineTransitionIfPossible(): void
    {
        $data = new \stdClass();
        $operation = new Create();
        $context = new Context();

        $this->operationStateMachine->expects($this->once())
            ->method('can')
            ->with($data, $operation, $context)
            ->willReturn(true);

        $this->operationStateMachine->expects($this->once())
            ->method('apply')
            ->with($data, $operation, $context);

        $this->writeProcessor->expects($this->once())
            ->method('process')
            ->with($data, $operation, $context)
            ->willReturn(null);

        $this->processor->process($data, $operation, $context);
    }

    public function testItDoesNothingWhenTransitionIsNotPossible(): void
    {
        $data = new \stdClass();
        $operation = new Create();
        $context = new Context();

        $this->operationStateMachine->expects($this->once())
            ->method('can')
            ->with($data, $operation, $context)
            ->willReturn(false);

        $this->operationStateMachine->expects($this->never())
            ->method('apply');

        $this->writeProcessor->expects($this->once())
            ->method('process')
            ->with($data, $operation, $context)
            ->willReturn(null);

        $this->assertNull($this->processor->process($data, $operation, $context));
    }
}
