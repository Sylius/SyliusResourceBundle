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

namespace spec\Sylius\Resource\State\Processor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface;
use Sylius\Resource\Symfony\EventDispatcher\State\DispatchPostWriteEventProcessor;
use Symfony\Component\HttpFoundation\Response;

final class DispatchPostWriteEventProcessorTest extends TestCase
{
    private ProcessorInterface|MockObject $processor;

    private OperationEventDispatcherInterface|MockObject $operationEventDispatcher;

    private OperationEventHandlerInterface|MockObject $eventHandler;

    private DispatchPostWriteEventProcessor $dispatchPostWriteEventProcessor;

    protected function setUp(): void
    {
        $this->processor = $this->createMock(ProcessorInterface::class);
        $this->operationEventDispatcher = $this->createMock(OperationEventDispatcherInterface::class);
        $this->eventHandler = $this->createMock(OperationEventHandlerInterface::class);

        $this->dispatchPostWriteEventProcessor = new DispatchPostWriteEventProcessor(
            $this->processor,
            $this->operationEventDispatcher,
            $this->eventHandler,
        );
    }

    /** @test */
    public function it_dispatches_post_events_with_operation_as_string(): void
    {
        $data = new \stdClass();

        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($data);

        $postEvent = new OperationEvent();

        $this->operationEventDispatcher->expects($this->once())->method('dispatchPostEvent')->with($data, $operation, $context)->willReturn($postEvent);

        $this->eventHandler->expects($this->once())->method('handlePostProcessEvent')->with($postEvent, $context)->willReturn(null);

        $result = $this->dispatchPostWriteEventProcessor->process($data, $operation, $context);
        $this->assertEquals($data, $result);
    }

    /** @test */
    public function it_returns_post_event_response(): void
    {
        $data = new \stdClass();
        $response = new Response();

        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($data);

        $postEvent = new OperationEvent();

        $this->operationEventDispatcher->expects($this->once())->method('dispatchPostEvent')->with($data, $operation, $context)->willReturn($postEvent);

        $this->eventHandler->expects($this->once())->method('handlePostProcessEvent')->with($postEvent, $context)->willReturn($response);

        $result = $this->dispatchPostWriteEventProcessor->process($data, $operation, $context);
        $this->assertEquals($response, $result);
    }

    /** @test */
    public function it_does_nothing_if_the_decorated_processor_returns_a_response(): void
    {
        $data = new \stdClass();

        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $response = new Response();

        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($response);

        $this->operationEventDispatcher->expects($this->never())->method('dispatchPostEvent');

        $this->eventHandler->expects($this->never())->method('handlePostProcessEvent');

        $this->dispatchPostWriteEventProcessor->process($data, $operation, $context);
    }
}
