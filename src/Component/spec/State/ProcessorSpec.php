<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Resource\State;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\State\Processor;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Component\Resource\Tests\Dummy\ProcessorWithCallable;

final class ProcessorSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator, OperationEventDispatcherInterface $operationEventDispatcher): void
    {
        $this->beConstructedWith($locator, $operationEventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Processor::class);
    }

    function it_calls_process_method_from_operation_processor_as_string(
        ContainerInterface $locator,
        ProcessorInterface $processor,
    ): void {
        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $locator->has('\App\Processor')->willReturn(true);
        $locator->get('\App\Processor')->willReturn($processor);

        $processor->process([], $operation, $context)->shouldBeCalled();

        $this->process([], $operation, $context);
    }

    function it_calls_process_method_from_operation_processor_as_callable(): void
    {
        $operation = new Create(processor: [ProcessorWithCallable::class, 'process']);
        $context = new Context();

        $this->process([], $operation, $context)->shouldHaveType(\stdClass::class);
    }

    function it_dispatches_pre_and_post_events_with_operation_as_string(
        ContainerInterface $locator,
        ProcessorInterface $processor,
        OperationEventDispatcherInterface $operationEventDispatcher,
        \stdClass $data,
    ): void {
        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $locator->has('\App\Processor')->willReturn(true);
        $locator->get('\App\Processor')->willReturn($processor);

        $processor->process($data, $operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatchPreEvent($data, $operation, $context)->shouldBeCalled();
        $operationEventDispatcher->dispatchPostEvent($data, $operation, $context)->shouldBeCalled();

        $this->process($data, $operation, $context);
    }

    function it_dispatches_pre_and_post_events_with_operation_as_callable(
        OperationEventDispatcherInterface $operationEventDispatcher,
        \stdClass $data,
    ): void {
        $operation = new Create(processor: [ProcessorWithCallable::class, 'process']);
        $context = new Context();

        $operationEventDispatcher->dispatchPreEvent($data, $operation, $context)->shouldBeCalled();
        $operationEventDispatcher->dispatchPostEvent($data, $operation, $context)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldHaveType(\stdClass::class);
    }

    function it_dispatches_events_for_each_items_with_bulk_operation_and_processor_as_callable(
        OperationEventDispatcherInterface $operationEventDispatcher,
        \stdClass $firstItem,
        \stdClass $secondItem,
    ): void {
        $operation = new BulkDelete(processor: [ProcessorWithCallable::class, 'process']);
        $context = new Context();

        $data = [
            $firstItem->getWrappedObject(),
            $secondItem->getWrappedObject(),
        ];

        $operationEventDispatcher->dispatchBulkEvent($data, $operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatchPreEvent($firstItem, $operation, $context)->shouldBeCalled();
        $operationEventDispatcher->dispatchPreEvent($secondItem, $operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatchPostEvent($firstItem, $operation, $context)->shouldBeCalled();
        $operationEventDispatcher->dispatchPostEvent($secondItem, $operation, $context)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn(null);
    }

    function it_dispatches_events_for_each_items_with_bulk_operation_and_processor_as_string(
        ContainerInterface $locator,
        ProcessorInterface $processor,
        OperationEventDispatcherInterface $operationEventDispatcher,
        \stdClass $firstItem,
        \stdClass $secondItem,
    ): void {
        $operation = new BulkDelete(processor: '\App\Processor');
        $context = new Context();

        $data = [
            $firstItem->getWrappedObject(),
            $secondItem->getWrappedObject(),
        ];

        $locator->has('\App\Processor')->willReturn(true);
        $locator->get('\App\Processor')->willReturn($processor);

        $processor->process($firstItem, $operation, $context)->shouldBeCalled();
        $processor->process($secondItem, $operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatchBulkEvent($data, $operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatchPreEvent($firstItem, $operation, $context)->shouldBeCalled();
        $operationEventDispatcher->dispatchPreEvent($secondItem, $operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatchPostEvent($firstItem, $operation, $context)->shouldBeCalled();
        $operationEventDispatcher->dispatchPostEvent($secondItem, $operation, $context)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn(null);
    }

    function it_returns_null_if_operation_has_no_processor(): void
    {
        $operation = new Create();
        $context = new Context();

        $this->process([], $operation, $context)->shouldReturn(null);
    }

    function it_throws_an_exception_when_configured_processor_is_not_a_processor_instance(
        ContainerInterface $locator,
    ): void {
        $operation = new Create(processor: '\stdClass');
        $context = new Context();

        $locator->has('\stdClass')->willReturn(true);
        $locator->get('\stdClass')->willReturn(new \stdClass());

        $this->shouldThrow(new \InvalidArgumentException('Expected an instance of Sylius\Component\Resource\State\ProcessorInterface. Got: stdClass'))
            ->during('process', [[], $operation, $context])
        ;
    }
}
