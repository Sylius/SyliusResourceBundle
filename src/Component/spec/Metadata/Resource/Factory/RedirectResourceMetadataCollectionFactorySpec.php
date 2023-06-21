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

namespace spec\Sylius\Component\Resource\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\RedirectResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Symfony\Routing\Factory\OperationRouteNameFactory;

final class RedirectResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(ResourceMetadataCollectionFactoryInterface $decorated): void
    {
        $this->beConstructedWith(new OperationRouteNameFactory(), $decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RedirectResourceMetadataCollectionFactory::class);
    }

    function it_redirects_create_to_show_if_route_exists(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

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
        $create->getRedirectToRoute()->shouldReturn('app_book_show');
    }

    function it_redirects_create_to_index_if_route_exists(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $index = (new Create(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getRedirectToRoute()->shouldReturn('app_book_index');
    }

    function it_redirects_update_to_show_if_route_exists(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

        $update = (new Update(name: 'app_book_update'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $update->getName() => $update,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $update = $resourceMetadataCollection->getOperation('app.book', 'app_book_update');
        $update->getRedirectToRoute()->shouldReturn('app_book_show');
    }

    function it_redirects_update_to_index_if_route_exists(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

        $update = (new Update(name: 'app_book_update'))->withResource($resource);
        $index = (new Index(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $update->getName() => $update,
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $update = $resourceMetadataCollection->getOperation('app.book', 'app_book_update');
        $update->getRedirectToRoute()->shouldReturn('app_book_index');
    }

    function it_redirects_delete_to_index_if_route_exists(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', name: 'book', applicationName: 'app');

        $delete = (new Delete(name: 'app_book_delete'))->withResource($resource);
        $index = (new Show(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $delete->getName() => $delete,
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $delete = $resourceMetadataCollection->getOperation('app.book', 'app_book_delete');
        $delete->getRedirectToRoute()->shouldReturn('app_book_index');
    }
}
