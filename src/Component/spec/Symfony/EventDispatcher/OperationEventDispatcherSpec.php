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
    private OperationEventDispatcher $operationEventDispatcher;

    private EventDispatcherInterface $eventDispatcher;

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
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $show = (new Show(eventShortName: 'read'))->withResource($resource);
        $context = new Context();
        $data = new \stdClass();

        $operationEvent = new OperationEvent($data, [
            'operation' => $show,
            'context' => $context,
        ]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($operationEvent, 'app.book.read');

        $this->operationEventDispatcher->dispatch($data, $show, $context);
    }

    public function testItDispatchesEventsForBulkOperations(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $bulkDelete = (new BulkDelete(eventShortName: 'delete'))->withResource($resource);
        $context = new Context();
        $data = new \ArrayObject();

        $operationEvent = new OperationEvent($data, [
            'operation' => $bulkDelete,
            'context' => $context,
        ]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($operationEvent, 'app.book.bulk_delete');

        $this->operationEventDispatcher->dispatchBulkEvent($data, $bulkDelete, $context);
    }

    public function testItDispatchesPreEvents(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(eventShortName: 'create'))->withResource($resource);
        $context = new Context();
        $data = new \stdClass();

        $operationEvent = new OperationEvent($data, [
            'operation' => $create,
            'context' => $context,
        ]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($operationEvent, 'app.book.pre_create');

        $this->operationEventDispatcher->dispatchPreEvent($data, $create, $context);
    }

    public function testItDispatchesPostEvents(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(eventShortName: 'create'))->withResource($resource);
        $context = new Context();
        $data = new \stdClass();

        $operationEvent = new OperationEvent($data, [
            'operation' => $create,
            'context' => $context,
        ]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($operationEvent, 'app.book.post_create');

        $this->operationEventDispatcher->dispatchPostEvent($data, $create, $context);
    }

    public function testItDispatchesInitializeEvents(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');
        $create = (new Create(eventShortName: 'create'))->withResource($resource);
        $context = new Context();
        $data = new \stdClass();

        $operationEvent = new OperationEvent($data, [
            'operation' => $create,
            'context' => $context,
        ]);

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($operationEvent, 'app.book.initialize_create');

        $this->operationEventDispatcher->dispatchInitializeEvent($data, $create, $context);
    }
}
