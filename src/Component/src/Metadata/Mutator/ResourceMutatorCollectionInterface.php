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

use Psr\Container\ContainerInterface;
use Sylius\Resource\Metadata\ResourceMutatorInterface;

/**
 * Collection of Resource mutators to mutate ResourceMetadata.
 */
interface ResourceMutatorCollectionInterface extends ContainerInterface
{
    /**
     * @return list<ResourceMutatorInterface>
     */
    public function get(string $id): array;
}
