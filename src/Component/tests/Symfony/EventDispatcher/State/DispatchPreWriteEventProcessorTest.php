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

namespace spec\Sylius\Resource\Symfony\EventDispatcher\State;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface;
use Sylius\Resource\Symfony\EventDispatcher\State\DispatchPreWriteEventProcessor;
use Symfony\Component\HttpFoundation\Response;

final class DispatchPreWriteEventProcessorTest extends TestCase
{
    private ProcessorInterface|MockObject $processor;

    private OperationEventDispatcherInterface|MockObject $operationEventDispatcher;

    private OperationEventHandlerInterface|MockObject $eventHandler;

    private DispatchPreWriteEventProcessor $dispatchPreWriteEventProcessor;

    protected function setUp(): void
    {
        $this->processor = $this->createMock(ProcessorInterface::class);
        $this->operationEventDispatcher = $this->createMock(OperationEventDispatcherInterface::class);
        $this->eventHandler = $this->createMock(OperationEventHandlerInterface::class);

        $this->dispatchPreWriteEventProcessor = new DispatchPreWriteEventProcessor(
            $this->processor,
            $this->operationEventDispatcher,
            $this->eventHandler,
        );
    }

    /** @test */
    public function it_dispatches_pre_events(): void
    {
        $data = new \stdClass();

        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $preEvent = new OperationEvent();

        $this->operationEventDispatcher->expects($this->once())->method('dispatchPreEvent')->with($data, $operation, $context)->willReturn($preEvent);

        $this->eventHandler->expects($this->once())->method('handlePreProcessEvent')->with($preEvent, $context, 'index')->willReturn(null);

        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($data);

        $result = $this->dispatchPreWriteEventProcessor->process($data, $operation, $context);
        $this->assertEquals($data, $result);
    }

    /** @test */
    public function it_does_not_call_processor_if_pre_event_returns_a_response(): void
    {
        $data = new \stdClass();
        $response = new Response();

        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $preEvent = new OperationEvent();

        $this->operationEventDispatcher->expects($this->once())->method('dispatchPreEvent')->with($data, $operation, $context)->willReturn($preEvent);

        $this->eventHandler->expects($this->once())->method('handlePreProcessEvent')->with($preEvent, $context, 'index')->willReturn($response);

        $this->processor->expects($this->never())->method('process');

        $result = $this->dispatchPreWriteEventProcessor->process($data, $operation, $context);
        $this->assertEquals($response, $result);
    }
}
