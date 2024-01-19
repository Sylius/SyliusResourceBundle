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

namespace Sylius\Resource\Doctrine\Persistence;

use Doctrine\Persistence\ObjectRepository;
use Sylius\Resource\Model\ResourceInterface;

/**
 * @template T of ResourceInterface
 *
 * @extends ObjectRepository<T>
 */
interface RepositoryInterface extends ObjectRepository
{
    public const ORDER_ASCENDING = 'ASC';

    public const ORDER_DESCENDING = 'DESC';

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string> $sorting
     *
     * @return iterable<T>
     */
    public function createPaginator(array $criteria = [], array $sorting = []): iterable;

    public function add(ResourceInterface $resource): void;

    public function remove(ResourceInterface $resource): void;
}

class_alias(RepositoryInterface::class, \Sylius\Component\Resource\Repository\RepositoryInterface::class);
