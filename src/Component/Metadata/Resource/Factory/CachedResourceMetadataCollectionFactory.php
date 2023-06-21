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

namespace Sylius\Component\Resource\Metadata\Resource\Factory;

use Psr\Cache\CacheException;
use Psr\Cache\CacheItemPoolInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;

final class CachedResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public const CACHE_KEY_PREFIX = 'sylius_resource_metadata_collection_';

    private array $localCache = [];

    public function __construct(
        private CacheItemPoolInterface $cacheItemPool,
        private ResourceMetadataCollectionFactoryInterface $decorated,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $cacheKey = self::CACHE_KEY_PREFIX . md5($resourceClass);
        if (\array_key_exists($cacheKey, $this->localCache)) {
            return new ResourceMetadataCollection($this->localCache[$cacheKey]);
        }

        try {
            $cacheItem = $this->cacheItemPool->getItem($cacheKey);
        } catch (CacheException) {
            $resourceMetadataCollection = $this->decorated->create($resourceClass);
            $this->localCache[$cacheKey] = (array) $resourceMetadataCollection;

            return $resourceMetadataCollection;
        }

        if ($cacheItem->isHit()) {
            $this->localCache[$cacheKey] = $cacheItem->get();

            return new ResourceMetadataCollection($this->localCache[$cacheKey]);
        }

        $resourceMetadataCollection = $this->decorated->create($resourceClass);
        $this->localCache[$cacheKey] = (array) $resourceMetadataCollection;
        $cacheItem->set($this->localCache[$cacheKey]);
        $this->cacheItemPool->save($cacheItem);

        return $resourceMetadataCollection;
    }
}
