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

namespace Sylius\Resource\Metadata\Mutator;

use Sylius\Resource\Metadata\OperationMutatorInterface;

/**
 * @internal
 */
final class OperationMutatorCollection implements OperationMutatorCollectionInterface
{
    /** @var array<string, list<OperationMutatorInterface>> */
    private array $mutators = [];

    /**
     * Adds a mutator to the container for a given operation name.
     */
    public function add(string $operationName, OperationMutatorInterface $mutator): void
    {
        $this->mutators[$operationName][] = $mutator;
    }

    public function get(string $id): array
    {
        return $this->mutators[$id] ?? [];
    }

    public function has(string $id): bool
    {
        return isset($this->mutators[$id]);
    }
}
