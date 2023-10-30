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

namespace spec\Sylius\Component\Resource\Symfony\EventDispatcher;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class OperationEventDispatcherSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher): void
    {
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationEventDispatcher::class);
    }

    function it_dispatches_events(
        EventDispatcherInterface $eventDispatcher,
        \stdClass $data,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $show = (new Show(eventShortName: 'read'))->withResource($resource);

        $context = new Context();

        $operationEvent = new OperationEvent($data->getWrappedObject(), [
            'operation' => $show,
            'context' => $context,
        ]);

        $eventDispatcher->dispatch($operationEvent, 'app.book.read')->shouldBeCalled();

        $this->dispatch($data, $show, $context)->shouldHaveType(OperationEvent::class);
    }

    function it_dispatches_events_for_bulk_operations(
        EventDispatcherInterface $eventDispatcher,
        \ArrayObject $data,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $bulkDelete = (new BulkDelete(eventShortName: 'delete'))->withResource($resource);

        $context = new Context();

        $operationEvent = new OperationEvent($data->getWrappedObject(), [
            'operation' => $bulkDelete,
            'context' => $context,
        ]);

        $eventDispatcher->dispatch($operationEvent, 'app.book.bulk_delete')->shouldBeCalled();

        $this->dispatchBulkEvent($data, $bulkDelete, $context)->shouldHaveType(OperationEvent::class);
    }

    function it_dispatches_pre_events(
        EventDispatcherInterface $eventDispatcher,
        \stdClass $data,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(eventShortName: 'create'))->withResource($resource);

        $context = new Context();

        $operationEvent = new OperationEvent($data->getWrappedObject(), [
            'operation' => $create,
            'context' => $context,
        ]);

        $eventDispatcher->dispatch($operationEvent, 'app.book.pre_create')->shouldBeCalled();

        $this->dispatchPreEvent($data, $create, $context)->shouldHaveType(OperationEvent::class);
    }

    function it_dispatches_post_events(
        EventDispatcherInterface $eventDispatcher,
        \stdClass $data,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(eventShortName: 'create'))->withResource($resource);

        $context = new Context();

        $operationEvent = new OperationEvent($data->getWrappedObject(), [
            'operation' => $create,
            'context' => $context,
        ]);

        $eventDispatcher->dispatch($operationEvent, 'app.book.post_create')->shouldBeCalled();

        $this->dispatchPostEvent($data, $create, $context)->shouldHaveType(OperationEvent::class);
    }

    function it_dispatches_initialize_events(
        EventDispatcherInterface $eventDispatcher,
        \stdClass $data,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(eventShortName: 'create'))->withResource($resource);

        $context = new Context();

        $operationEvent = new OperationEvent($data->getWrappedObject(), [
            'operation' => $create,
            'context' => $context,
        ]);

        $eventDispatcher->dispatch($operationEvent, 'app.book.initialize_create')->shouldBeCalled();

        $this->dispatchInitializeEvent($data, $create, $context)->shouldHaveType(OperationEvent::class);
    }
}
