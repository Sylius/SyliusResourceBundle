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

namespace spec\Sylius\Component\Resource\State;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\State\EventDispatcherBulkAwareProcessor;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Component\Resource\Tests\Dummy\ProcessorWithCallable;

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
