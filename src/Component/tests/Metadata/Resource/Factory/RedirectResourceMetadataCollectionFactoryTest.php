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
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\RedirectResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;

final class RedirectResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    private RedirectResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new RedirectResourceMetadataCollectionFactory(
            new OperationRouteNameFactory(),
            $this->decorated->reveal(),
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(RedirectResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItRedirectsCreateToShowIfRouteExists(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertSame('app_book_show', $create->getRedirectToRoute());
    }

    public function testItRedirectsCreateToIndexIfRouteExists(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $index = (new Index(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertSame('app_book_index', $create->getRedirectToRoute());
    }

    public function testItRedirectsUpdateToShowIfRouteExists(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $update = (new Update(name: 'app_book_update'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $update->getName() => $update,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $update = $resourceMetadataCollection->getOperation('app.book', 'app_book_update');
        $this->assertSame('app_book_show', $update->getRedirectToRoute());
    }

    public function testItRedirectsUpdateToIndexIfRouteExists(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $update = (new Update(name: 'app_book_update'))->withResource($resource);
        $index = (new Index(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $update->getName() => $update,
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $update = $resourceMetadataCollection->getOperation('app.book', 'app_book_update');
        $this->assertSame('app_book_index', $update->getRedirectToRoute());
    }

    public function testItRedirectsDeleteToIndexIfRouteExists(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $delete = (new Delete(name: 'app_book_delete'))->withResource($resource);
        $index = (new Show(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $delete->getName() => $delete,
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $delete = $resourceMetadataCollection->getOperation('app.book', 'app_book_delete');
        $this->assertSame('app_book_index', $delete->getRedirectToRoute());
    }
}
