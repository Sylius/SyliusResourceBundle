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

use Sylius\Resource\Metadata\ResourceMutatorInterface;

/**
 * @internal
 */
final class ResourceMutatorCollection implements ResourceMutatorCollectionInterface
{
    /** @var array<string, list<ResourceMutatorInterface>> */
    private array $mutators = [];

    public function add(string $resourceClass, ResourceMutatorInterface $mutator): void
    {
        $this->mutators[$resourceClass][] = $mutator;
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
