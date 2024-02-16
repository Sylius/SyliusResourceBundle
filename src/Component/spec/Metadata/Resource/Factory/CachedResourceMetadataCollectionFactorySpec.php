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

namespace spec\Sylius\Resource\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sylius\Resource\Metadata\Resource\Factory\CachedResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Symfony\Component\Cache\Exception\CacheException;

final class CachedResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(
        CacheItemPoolInterface $cacheItemPool,
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($cacheItemPool, $decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CachedResourceMetadataCollectionFactory::class);
    }

    function it_uses_decorated_factory_when_cache_is_not_available(
        CacheItemPoolInterface $cacheItemPool,
        CacheItemInterface $cacheItem,
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $cacheItemPool->getItem(Argument::cetera())->willReturn($cacheItem);

        $cacheItem->isHit()->willReturn(false);

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $cacheItem->set(Argument::cetera())->willReturn($cacheItem)->shouldBeCalled();

        $cacheItemPool->save(Argument::cetera())->willReturn(true)->shouldBeCalled();

        $this->create('App\Resource')->shouldReturn($resourceMetadataCollection);
    }

    function it_retrieves_cache_when_it_is_available(
        CacheItemPoolInterface $cacheItemPool,
        CacheItemInterface $cacheItem,
    ): void {
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $cacheItemPool->getItem(Argument::cetera())->willReturn($cacheItem);

        $cacheItem->isHit()->willReturn(true);
        $cacheItem->get()->willReturn()->willReturn($resourceMetadataCollection);

        $result = $this->create('App\Resource');
        $result->shouldHaveType(ResourceMetadataCollection::class);
    }

    function it_uses_local_cache_when_cache_pool_is_not_available(
        CacheItemPoolInterface $cacheItemPool,
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resourceMetadataCollection = new ResourceMetadataCollection();

        $cacheItemPool->getItem(Argument::cetera())->willThrow(new CacheException());

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection)->shouldBeCalled();

        $this->create('App\Resource')->shouldReturn($resourceMetadataCollection);
    }
}
