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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/** @psalm-suppress DeprecatedInterface */
final class ResourceLogEntryRepository extends EntityRepository implements ResourceLogEntryRepositoryInterface
{
    public function createByObjectIdQueryBuilder(string $objectId): QueryBuilder
    {
        return $this->createQueryBuilder('log')
            ->where('log.objectId = :objectId')
            ->setParameter('objectId', $objectId)
        ;
    }
}
