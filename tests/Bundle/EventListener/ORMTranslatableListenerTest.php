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

namespace Bundle\EventListener;

use App\Entity\BookTranslationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ORMTranslatableListenerTest extends KernelTestCase
{
    public function testGettingTranslationRepositoryByItsInterface(): void
    {
        self::bootKernel();

        $this->assertInstanceOf(
            EntityRepository::class,
            $this->getEntityManager()->getRepository(BookTranslationInterface::class),
        );
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return $this->getContainer()->get(EntityManagerInterface::class);
    }
}
