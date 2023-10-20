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
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\Factory\VarsResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Metadata\Show;

final class VarsResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(ResourceMetadataCollectionFactoryInterface $decorated): void
    {
        $this->beConstructedWith($decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(VarsResourceMetadataCollectionFactory::class);
    }

    function it_merge_resource_vars_with_operation_vars(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app', vars: ['header' => 'Library', 'subheader' => 'Managing your library']);

        $create = (new Create(name: 'app_book_create', vars: ['subheader' => 'Adding a new book']))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getVars()->shouldReturn([
            'header' => 'Library',
            'subheader' => 'Adding a new book',
        ]);
    }

    function it_does_nothing_when_resource_has_no_vars(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getVars()->shouldReturn(null);
    }
}
