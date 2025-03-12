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

namespace Sylius\Resource\Metadata\Resource;

/**
 * A collection of resource names.
 *
 * @experimental
 */
final class ResourceNameCollection implements \IteratorAggregate, \Countable
{
    /**
     * @param string[] $names
     */
    public function __construct(private readonly array $names = [])
    {
    }

    /**
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->names);
    }

    public function count(): int
    {
        return \count($this->names);
    }
}
