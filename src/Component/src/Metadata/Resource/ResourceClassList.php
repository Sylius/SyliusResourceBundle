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
 * A list of resource class names.
 *
 * @experimental
 */
final class ResourceClassList implements \IteratorAggregate, \Countable
{
    /**
     * @param string[] $classes
     */
    public function __construct(private readonly array $classes = [])
    {
    }

    /**
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->classes);
    }

    public function count(): int
    {
        return \count($this->classes);
    }
}
