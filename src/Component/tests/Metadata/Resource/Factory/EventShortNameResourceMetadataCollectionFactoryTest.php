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

namespace Sylius\Resource\Tests\Metadata\Resource\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Metadata\ApplyStateMachineTransition;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\EventShortNameResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;

final class EventShortNameResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    private EventShortNameResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new EventShortNameResourceMetadataCollectionFactory($this->decorated->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(EventShortNameResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItConfiguresDefaultEventShortNameOnOperations(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);
        $applyStateMachineTransition = (new ApplyStateMachineTransition(name: 'app_book_publish'))->withResource($resource);
        $bulkDelete = (new BulkDelete(name: 'app_book_bulk_delete'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
            $applyStateMachineTransition->getName() => $applyStateMachineTransition,
            $bulkDelete->getName() => $bulkDelete,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertSame('create', $create->getEventShortName());

        $show = $resourceMetadataCollection->getOperation('app.book', 'app_book_show');
        $this->assertSame('show', $show->getEventShortName());

        $publish = $resourceMetadataCollection->getOperation('app.book', 'app_book_publish');
        $this->assertSame('update', $publish->getEventShortName());

        $bulkDelete = $resourceMetadataCollection->getOperation('app.book', 'app_book_bulk_delete');
        $this->assertSame('delete', $bulkDelete->getEventShortName());
    }
}
