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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB;

use Doctrine\MongoDB\Query\Builder as QueryBuilder;
use Sylius\Component\Resource\Repository\TranslatableRepositoryInterface;

trigger_deprecation('sylius/resource-bundle', '1.3', 'The "%s" class is deprecated. Doctrine MongoDB and PHPCR support will no longer be supported in 2.0.', TranslatableRepository::class);

/**
 * Doctrine ORM driver translatable entity repository.
 */
class TranslatableRepository extends DocumentRepository implements TranslatableRepositoryInterface
{
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null): void
    {
        if (null === $criteria) {
            return;
        }

        foreach ($criteria as $property => $value) {
            if (is_array($value)) {
                $queryBuilder
                    ->field($property)->in($value)
                ;
            } elseif ('' !== $value) {
                $queryBuilder
                    ->field($property)->equals($value)
                ;
            }
        }
    }

    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = null): void
    {
        if (null === $sorting) {
            return;
        }

        foreach ($sorting as $property => $order) {
            $queryBuilder->sort($property, $order);
        }
    }
}
