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

namespace spec\Sylius\Component\Resource\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\ApplyStateMachineTransition;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\EventShortNameResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\Show;

final class EventShortNameResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(EventShortNameResourceMetadataCollectionFactory::class);
    }

    function it_configures_default_event_short_name_on_operations(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

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

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getEventShortName()->shouldReturn('create');

        $show = $resourceMetadataCollection->getOperation('app.book', 'app_book_show');
        $show->getEventShortName()->shouldReturn('show');

        $publish = $resourceMetadataCollection->getOperation('app.book', 'app_book_publish');
        $publish->getEventShortName()->shouldReturn('update');

        $bulkDelete = $resourceMetadataCollection->getOperation('app.book', 'app_book_bulk_delete');
        $bulkDelete->getEventShortName()->shouldReturn('delete');
    }
}
