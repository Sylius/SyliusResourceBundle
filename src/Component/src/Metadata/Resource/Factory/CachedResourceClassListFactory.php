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

namespace Sylius\Resource\Metadata\Resource\Factory;

use Psr\Cache\CacheItemPoolInterface;
use Sylius\Resource\Metadata\Resource\ResourceClassList;
use Sylius\Resource\Metadata\Util\CachedTrait;

/**
 * Caches resource class list.
 */
final class CachedResourceClassListFactory implements ResourceClassListFactoryInterface
{
    use CachedTrait;

    public const CACHE_KEY = 'resource_class_list';

    public function __construct(CacheItemPoolInterface $cacheItemPool, private readonly ResourceClassListFactoryInterface $decorated)
    {
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @inheritdoc
     */
    public function create(): ResourceClassList
    {
        return $this->getCached(self::CACHE_KEY, fn (): ResourceClassList => $this->decorated->create());
    }
}
