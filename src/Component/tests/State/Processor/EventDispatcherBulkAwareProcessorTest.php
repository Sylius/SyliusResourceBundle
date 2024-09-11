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

namespace Sylius\Resource\Tests\State\Processor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Tests\Dummy\ProcessorWithCallable;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\State\Processor\EventDispatcherBulkAwareProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

final class EventDispatcherBulkAwareProcessorTest extends TestCase
{
    private EventDispatcherBulkAwareProcessor $processor;

    private ProcessorInterface|MockObject $decorated;

    private OperationEventDispatcherInterface|MockObject $operationEventDispatcher;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ProcessorInterface::class);
        $this->operationEventDispatcher = $this->createMock(OperationEventDispatcherInterface::class);
        $this->processor = new EventDispatcherBulkAwareProcessor(
            $this->decorated,
            $this->operationEventDispatcher,
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(EventDispatcherBulkAwareProcessor::class, $this->processor);
    }

    public function testItDispatchesEventsForBulkOperation(): void
    {
        $operation = new BulkDelete(processor: [ProcessorWithCallable::class, 'process']);
        $context = new Context();
        $operationEvent = new OperationEvent();
        $data = [];

        $this->operationEventDispatcher->expects($this->once())
            ->method('dispatchBulkEvent')
            ->with($data, $operation, $context)
            ->willReturn($operationEvent);

        $this->decorated->expects($this->once())
            ->method('process')
            ->with($data, $operation, $context)
            ->willReturn(null);

        $this->processor->process($data, $operation, $context);
    }
}
