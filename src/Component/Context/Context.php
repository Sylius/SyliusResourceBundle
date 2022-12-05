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

namespace Sylius\Component\Resource\Context;

/**
 * @implements \IteratorAggregate<object>
 */
final class Context implements \IteratorAggregate
{
    /** @var array<class-string, object> */
    private array $optionMap;

    /**
     * @param object|array<class-string, object> ...$options
     */
    public function __construct(object|array ...$options)
    {
        $map = [];
        foreach ($options as $option) {
            if (\is_array($option)) {
                $map = array_merge($map, $option);

                continue;
            }

            $map[get_class($option)] = $option;
        }

        $this->optionMap = $map;
    }

    public function with(object|array ...$options): self
    {
        /** @psalm-suppress DuplicateArrayKey */
        return new self(...[
            ...array_values($this->optionMap),
            ...$options,
        ]);
    }

    /**
     * @param class-string $optionClasses
     */
    public function without(string ...$optionClasses): self
    {
        $optionMap = $this->optionMap;

        foreach ($optionClasses as $optionClass) {
            unset($optionMap[$optionClass]);
        }

        return new self(...array_values($optionMap));
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $optionClass
     *
     * @return T|null
     */
    public function get(string $optionClass): ?object
    {
        /** @var T $option */
        $option = $this->optionMap[$optionClass] ?? null;

        return $option;
    }

    /**
     * @return \Traversable<object>
     */
    public function getIterator(): \Traversable
    {
        yield from array_values($this->optionMap);
    }
}
