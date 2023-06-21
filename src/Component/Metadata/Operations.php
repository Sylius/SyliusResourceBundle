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

namespace  Sylius\Component\Resource\Metadata;

use RuntimeException;

/**
 * @internal
 */
final class Operations implements \IteratorAggregate, \Countable
{
    private array $operations = [];

    /**
     * @param array<string|int, Operation> $operations
     */
    public function __construct(array $operations = [])
    {
        foreach ($operations as $operationName => $operation) {
            $this->operations[] = [$operationName, $operation];
        }
    }

    public function getIterator(): \Traversable
    {
        return (function (): \Generator {
            foreach ($this->operations as [$operationName, $operation]) {
                yield $operationName => $operation;
            }
        })();
    }

    public function get(string $key): Operation
    {
        foreach ($this->operations as [$operationName, $operation]) {
            if ($operationName === $key) {
                return $operation;
            }
        }

        throw new \RuntimeException(sprintf('No Operation with key "%s" was found', $key));
    }

    public function add(string $key, Operation $value): self
    {
        foreach ($this->operations as $i => [$operationName, $operation]) {
            if ($operationName === $key) {
                $this->operations[$i] = [$key, $value];

                return $this;
            }
        }

        $this->operations[] = [$key, $value];

        return $this;
    }

    public function remove(string $key): self
    {
        foreach ($this->operations as $i => [$operationName, $operation]) {
            if ($operationName === $key) {
                unset($this->operations[$i]);

                return $this;
            }
        }

        throw new RuntimeException(sprintf('Could not remove operation "%s".', $key));
    }

    public function has(string $key): bool
    {
        foreach ($this->operations as $i => [$operationName, $operation]) {
            if ($operationName === $key) {
                return true;
            }
        }

        return false;
    }

    public function count(): int
    {
        return \count($this->operations);
    }
}
