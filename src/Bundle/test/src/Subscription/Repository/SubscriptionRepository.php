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

namespace App\Subscription\Repository;

use App\Subscription\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class SubscriptionRepository extends ServiceEntityRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function createListQueryBuilder(bool $showArchived = false): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if (!$showArchived) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->isNull('o.archivedAt'),
            );
        }

        return $queryBuilder;
    }
}
