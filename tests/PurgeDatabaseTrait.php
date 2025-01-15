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

namespace Tests;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Before;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait PurgeDatabaseTrait
{
    #[Before]
    protected function _purgeDatabase(): void
    {
        if (!\is_subclass_of(static::class, KernelTestCase::class)) {
            throw new \RuntimeException(\sprintf('The "%s" trait can only be used on TestCases that extend "%s".', __TRAIT__, KernelTestCase::class));
        }

        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $purger = new ORMPurger($entityManager);
        $purger->purge();

        $entityManager->clear();
    }
}
