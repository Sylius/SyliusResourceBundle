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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
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
    use ProphecyTrait;

    private ObjectProphecy|ProcessorInterface $processor;

    private ObjectProphecy|OperationEventDispatcherInterface $operationEventDispatcher;

    private ObjectProphecy|OperationEventHandlerInterface $eventHandler;

    private DispatchPostWriteEventProcessor $dispatchPostWriteEventProcessor;

    protected function setUp(): void
    {
        $this->processor = $this->prophesize(ProcessorInterface::class);
        $this->operationEventDispatcher = $this->prophesize(OperationEventDispatcherInterface::class);
        $this->eventHandler = $this->prophesize(OperationEventHandlerInterface::class);

        $this->dispatchPostWriteEventProcessor = new DispatchPostWriteEventProcessor(
            $this->processor->reveal(),
            $this->operationEventDispatcher->reveal(),
            $this->eventHandler->reveal(),
        );
    }

    /** @test */
    public function it_dispatches_post_events_with_operation_as_string(): void
    {
        $data = new \stdClass();

        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $this->processor->process($data, $operation, $context)->willReturn($data)->shouldBeCalled();

        $preEvent = new OperationEvent();
        $postEvent = new OperationEvent();

        $this->operationEventDispatcher->dispatchPostEvent($data, $operation, $context)->willReturn($postEvent)->shouldBeCalled();

        $this->eventHandler->handlePostProcessEvent($postEvent, $context)->willReturn(null)->shouldBeCalled();

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

        $this->processor->process($data, $operation, $context)->willReturn($data)->shouldBeCalled();

        $postEvent = new OperationEvent();

        $this->operationEventDispatcher->dispatchPostEvent($data, $operation, $context)->willReturn($postEvent)->shouldBeCalled();

        $this->eventHandler->handlePostProcessEvent($postEvent, $context)->willReturn($response)->shouldBeCalled();

        $result = $this->dispatchPostWriteEventProcessor->process($data, $operation, $context);
        $this->assertEquals($response, $result);
    }
}
