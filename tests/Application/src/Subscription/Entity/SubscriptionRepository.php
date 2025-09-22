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

namespace App\Subscription\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\CreatePaginatorTrait;

final class SubscriptionRepository extends ServiceEntityRepository
{
    use CreatePaginatorTrait;

    public function __construct(
        public readonly ManagerRegistry $registry,
    ) {
        parent::__construct($this->registry, Subscription::class);
    }
}
