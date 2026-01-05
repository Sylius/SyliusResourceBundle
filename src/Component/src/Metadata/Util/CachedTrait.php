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

namespace Sylius\Resource\Metadata\Util;

use Psr\Cache\CacheException;
use Psr\Cache\CacheItemPoolInterface;

/**
 * This trait in inspired by this API Platform one:
 *
 * @see https://github.com/api-platform/core/blob/main/src/Metadata/Util/CachedTrait.php
 *
 * @internal
 */
trait CachedTrait
{
    private CacheItemPoolInterface $cacheItemPool;

    /** @var array<string, mixed> */
    private array $localCache = [];

    private function getCached(string $cacheKey, callable $getValue): mixed
    {
        if (\array_key_exists($cacheKey, $this->localCache)) {
            return $this->localCache[$cacheKey];
        }

        try {
            $cacheItem = $this->cacheItemPool->getItem($cacheKey);
        } catch (CacheException) {
            return $this->localCache[$cacheKey] = $getValue();
        }

        if ($cacheItem->isHit()) {
            return $this->localCache[$cacheKey] = $cacheItem->get();
        }

        $value = $getValue();

        $cacheItem->set($value);
        $this->cacheItemPool->save($cacheItem);

        return $this->localCache[$cacheKey] = $value;
    }
}
