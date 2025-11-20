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

namespace Sylius\Resource\Tests\Symfony\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class OperationEventDispatcherTest extends TestCase
{
    private EventDispatcherInterface $eventDispatcher;

    private OperationEventDispatcher $operationEventDispatcher;

    protected function setUp(): void
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->operationEventDispatcher = new OperationEventDispatcher($this->eventDispatcher);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(OperationEventDispatcher::class, $this->operationEventDispatcher);
    }

    public function testItDispatchesEvents(): void
    {
        $data = new \stdClass();
        $resource = $this->createResource();
        $show = (new Show(eventShortName: 'read'))->withResource($resource);
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(fn (OperationEvent $event) => $event->getSubject() === $data),
                'app.book.read',
            )
            ->willReturnArgument(0);

        $result = $this->operationEventDispatcher->dispatch($data, $show, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
    }

    public function testItDispatchesEventsForBulkOperations(): void
    {
        $data = new \ArrayObject();
        $resource = $this->createResource();
        $bulkDelete = (new BulkDelete(eventShortName: 'delete'))->withResource($resource);
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(fn (OperationEvent $event) => $event->getSubject() === $data),
                'app.book.bulk_delete',
            )
            ->willReturnArgument(0);

        $result = $this->operationEventDispatcher->dispatchBulkEvent($data, $bulkDelete, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
    }

    public function testItDispatchesPreEvents(): void
    {
        $data = new \stdClass();
        $resource = $this->createResource();
        $create = (new Create(eventShortName: 'create'))->withResource($resource);
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(fn (OperationEvent $event) => $event->getSubject() === $data),
                'app.book.pre_create',
            )
            ->willReturnArgument(0);

        $result = $this->operationEventDispatcher->dispatchPreEvent($data, $create, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
    }

    public function testItDispatchesPostEvents(): void
    {
        $data = new \stdClass();
        $resource = $this->createResource();
        $create = (new Create(eventShortName: 'create'))->withResource($resource);
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(fn (OperationEvent $event) => $event->getSubject() === $data),
                'app.book.post_create',
            )
            ->willReturnArgument(0);

        $result = $this->operationEventDispatcher->dispatchPostEvent($data, $create, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
    }

    public function testItDispatchesInitializeEvents(): void
    {
        $data = new \stdClass();
        $resource = $this->createResource();
        $create = (new Create(eventShortName: 'create'))->withResource($resource);
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->callback(fn (OperationEvent $event) => $event->getSubject() === $data),
                'app.book.initialize_create',
            )
            ->willReturnArgument(0);

        $result = $this->operationEventDispatcher->dispatchInitializeEvent($data, $create, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
    }

    public function testItReturnsEventWithoutDispatchingWhenResourceIsNull(): void
    {
        $data = new \stdClass();
        $create = new Create();
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $result = $this->operationEventDispatcher->dispatch($data, $create, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
        $this->assertSame($data, $result->getSubject());
    }

    public function testItReturnsEventWithoutDispatchingWhenResourceIsNullForBulkEvent(): void
    {
        $data = new \ArrayObject();
        $bulkDelete = new BulkDelete();
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $result = $this->operationEventDispatcher->dispatchBulkEvent($data, $bulkDelete, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
        $this->assertSame($data, $result->getSubject());
    }

    public function testItReturnsEventWithoutDispatchingWhenResourceIsNullForPreEvent(): void
    {
        $data = new \stdClass();
        $create = new Create();
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $result = $this->operationEventDispatcher->dispatchPreEvent($data, $create, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
        $this->assertSame($data, $result->getSubject());
    }

    public function testItReturnsEventWithoutDispatchingWhenResourceIsNullForPostEvent(): void
    {
        $data = new \stdClass();
        $create = new Create();
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $result = $this->operationEventDispatcher->dispatchPostEvent($data, $create, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
        $this->assertSame($data, $result->getSubject());
    }

    public function testItReturnsEventWithoutDispatchingWhenResourceIsNullForInitializeEvent(): void
    {
        $data = new \stdClass();
        $create = new Create();
        $context = new Context();

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $result = $this->operationEventDispatcher->dispatchInitializeEvent($data, $create, $context);

        $this->assertInstanceOf(OperationEvent::class, $result);
        $this->assertSame($data, $result->getSubject());
    }

    private function createResource(): ResourceMetadata
    {
        return new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
    }
}
