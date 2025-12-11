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
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sylius\Resource\Metadata\Resource\Factory\CachedResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Symfony\Component\Cache\Exception\CacheException;

final class CachedResourceMetadataCollectionFactoryTest extends TestCase
{
    private CacheItemPoolInterface|MockObject $cacheItemPool;

    private ResourceMetadataCollectionFactoryInterface|MockObject $decorated;

    protected function setUp(): void
    {
        $this->cacheItemPool = $this->createMock(CacheItemPoolInterface::class);
        $this->decorated = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
    }

    public function testItIsInitializable(): void
    {
        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool,
            $this->decorated,
        );

        $this->assertInstanceOf(CachedResourceMetadataCollectionFactory::class, $factory);
    }

    public function testItUsesDecoratedFactoryWhenCacheIsNotAvailable(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $this->cacheItemPool->method('getItem')->willReturn($cacheItem);

        $cacheItem->method('isHit')->willReturn(false);
        $this->decorated->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $cacheItem->expects($this->once())->method('set')->with((array) $resourceMetadataCollection)->willReturn($cacheItem);
        $this->cacheItemPool->expects($this->once())->method('save')->with($cacheItem)->willReturn(true);

        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool,
            $this->decorated,
        );

        $this->assertSame($resourceMetadataCollection, $factory->create('App\Resource'));
    }

    public function testItRetrievesCacheWhenItIsAvailable(): void
    {
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $this->cacheItemPool->method('getItem')->willReturn($cacheItem);
        $cacheItem->method('isHit')->willReturn(true);
        $cacheItem->method('get')->willReturn($resourceMetadataCollection);

        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool,
            $this->decorated,
        );

        $result = $factory->create('App\Resource');
        $this->assertInstanceOf(ResourceMetadataCollection::class, $result);
    }

    public function testItUsesLocalCacheWhenCachePoolIsNotAvailable(): void
    {
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $this->cacheItemPool->method('getItem')->willThrowException(new CacheException());
        $this->decorated->expects($this->once())->method('create')->with('App\Resource')->willReturn($resourceMetadataCollection);

        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool,
            $this->decorated,
        );

        $this->assertSame($resourceMetadataCollection, $factory->create('App\Resource'));
    }
}
