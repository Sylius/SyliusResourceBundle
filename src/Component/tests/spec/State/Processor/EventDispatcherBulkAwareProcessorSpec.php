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

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Tests\Dummy\ProcessorWithCallable;
use Sylius\Resource\Context\Context;
use Sylius\Resource\State\Processor\EventDispatcherBulkAwareProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

final class EventDispatcherBulkAwareProcessorSpec extends ObjectBehavior
{
    function let(ProcessorInterface $decorated, OperationEventDispatcherInterface $operationEventDispatcher): void
    {
        $this->beConstructedWith($decorated, $operationEventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(EventDispatcherBulkAwareProcessor::class);
    }

    function it_dispatches_events_for_bulk_operation(
        ProcessorInterface $decorated,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $operation = new BulkDelete(processor: [ProcessorWithCallable::class, 'process']);
        $context = new Context();

        $operationEvent = new OperationEvent();

        $data = [];

        $operationEventDispatcher->dispatchBulkEvent($data, $operation, $context)->willReturn($operationEvent)->shouldBeCalled();

        $decorated->process($data, $operation, $context)->shouldBeCalled();

        $this->process($data, $operation, $context);
    }
}
