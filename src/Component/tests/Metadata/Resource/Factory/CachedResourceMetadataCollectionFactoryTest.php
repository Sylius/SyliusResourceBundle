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
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sylius\Resource\Metadata\Resource\Factory\CachedResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Symfony\Component\Cache\Exception\CacheException;

final class CachedResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private CacheItemPoolInterface|ObjectProphecy $cacheItemPool;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    protected function setUp(): void
    {
        $this->cacheItemPool = $this->prophesize(CacheItemPoolInterface::class);
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
    }

    public function testItIsInitializable(): void
    {
        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool->reveal(),
            $this->decorated->reveal(),
        );

        $this->assertInstanceOf(CachedResourceMetadataCollectionFactory::class, $factory);
    }

    public function testItUsesDecoratedFactoryWhenCacheIsNotAvailable(): void
    {
        $cacheItem = $this->prophesize(CacheItemInterface::class);
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $this->cacheItemPool->getItem(Argument::cetera())->willReturn($cacheItem);

        $cacheItem->isHit()->willReturn(false);
        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $cacheItem->set((array) $resourceMetadataCollection)->willReturn($cacheItem)->shouldBeCalled();
        $this->cacheItemPool->save($cacheItem)->willReturn(true)->shouldBeCalled();

        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool->reveal(),
            $this->decorated->reveal(),
        );

        $this->assertSame($resourceMetadataCollection, $factory->create('App\Resource'));
    }

    public function testItRetrievesCacheWhenItIsAvailable(): void
    {
        $cacheItem = $this->prophesize(CacheItemInterface::class);
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $this->cacheItemPool->getItem(Argument::cetera())->willReturn($cacheItem);
        $cacheItem->isHit()->willReturn(true);
        $cacheItem->get()->willReturn($resourceMetadataCollection);

        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool->reveal(),
            $this->decorated->reveal(),
        );

        $result = $factory->create('App\Resource');
        $this->assertInstanceOf(ResourceMetadataCollection::class, $result);
    }

    public function testItUsesLocalCacheWhenCachePoolIsNotAvailable(): void
    {
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $this->cacheItemPool->getItem(Argument::cetera())->willThrow(new CacheException());
        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection)->shouldBeCalled();

        $factory = new CachedResourceMetadataCollectionFactory(
            $this->cacheItemPool->reveal(),
            $this->decorated->reveal(),
        );

        $this->assertSame($resourceMetadataCollection, $factory->create('App\Resource'));
    }
}
