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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Grid\State\RequestGridProvider;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\ProviderResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Request\State\Provider;

final class ProviderResourceMetadataCollectionFactoryTest extends TestCase
{
    private ResourceMetadataCollectionFactoryInterface|MockObject $decorated;

    private ProviderResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new ProviderResourceMetadataCollectionFactory($this->decorated);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ProviderResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItCreatesResourceMetadataWithDefaultProviderOnHttpOperations(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $index = (new Index(name: 'app_book_index'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $index = $resourceMetadataCollection->getOperation('app.book', 'app_book_index');
        $this->assertSame(Provider::class, $index->getProvider());
    }

    public function testItConfiguresRequestGridProviderIfOperationHasAGrid(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', name: 'book', applicationName: 'app');

        $index = (new Index(name: 'app_book_index', grid: 'app_book'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $index->getName() => $index,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $index = $resourceMetadataCollection->getOperation('app.book', 'app_book_index');
        $this->assertSame(RequestGridProvider::class, $index->getProvider());
    }
}
