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

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Sylius\Resource\Model\ResourceInterface;

/**
 * @mixin DoctrineEntityRepository
 */
trait ResourceRepositoryTrait
{
    use CreatePaginatorTrait;

    public function add(ResourceInterface $resource): void
    {
        $this->getEntityManager()->persist($resource);
        $this->getEntityManager()->flush();
    }

    public function remove(ResourceInterface $resource): void
    {
        if (null !== $this->find($resource->getId())) {
            $this->getEntityManager()->remove($resource);
            $this->getEntityManager()->flush();
        }
    }
}
