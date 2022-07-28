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

namespace Sylius\Component\Resource\Repository;

use Doctrine\Persistence\ObjectRepository;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @template T of ResourceInterface
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

    /**
     * @param T $resource
     */
    public function add(ResourceInterface $resource): void;

    /**
     * @param T $resource
     */
    public function remove(ResourceInterface $resource): void;
}
