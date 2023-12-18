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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Resource\src\Symfony\EventDispatcher\State\DispatchPreWriteEventProcessor;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

final class DispatchPreWriteEventProcessorTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|ProcessorInterface $processor;

    private ObjectProphecy|OperationEventDispatcherInterface $operationEventDispatcher;

    private ObjectProphecy|OperationEventHandlerInterface $eventHandler;

    private DispatchPreWriteEventProcessor $dispatchPreWriteEventProcessor;

    protected function setUp(): void
    {
        $this->processor = $this->prophesize(ProcessorInterface::class);
        $this->operationEventDispatcher = $this->prophesize(OperationEventDispatcherInterface::class);
        $this->eventHandler = $this->prophesize(OperationEventHandlerInterface::class);

        $this->dispatchPreWriteEventProcessor = new DispatchPreWriteEventProcessor(
            $this->processor->reveal(),
            $this->operationEventDispatcher->reveal(),
            $this->eventHandler->reveal(),
        );
    }

    /** @test */
    public function it_dispatches_pre_events_with_operation_as_string(): void
    {
        $data = new \stdClass();

        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $this->processor->process($data, $operation, $context)->willReturn($data)->shouldBeCalled();

        $preEvent = new OperationEvent();

        $this->operationEventDispatcher->dispatchPreEvent($data, $operation, $context)->willReturn($preEvent)->shouldBeCalled();

        $this->eventHandler->handlePreProcessEvent($preEvent, $context, 'index')->willReturn(null)->shouldBeCalled();

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

        $this->processor->process($data, $operation, $context)->willReturn($data)->shouldNotBeCalled();

        $preEvent = new OperationEvent();

        $this->operationEventDispatcher->dispatchPreEvent($data, $operation, $context)->willReturn($preEvent)->shouldBeCalled();

        $this->eventHandler->handlePreProcessEvent($preEvent, $context, 'index')->willReturn($response)->shouldBeCalled();

        $result = $this->dispatchPreWriteEventProcessor->process($data, $operation, $context);
        $this->assertEquals($response, $result);
    }
}
