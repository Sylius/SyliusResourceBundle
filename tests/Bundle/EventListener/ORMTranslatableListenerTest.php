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

use App\Entity\BookTranslation;
use App\Entity\BookTranslationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Sylius\Bundle\ResourceBundle\EventListener\ORMTranslatableListener;
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

    public function testItAddsTheTranslatableUniqueConstraintWhenAnotherOneUsesTheFieldsFormat(): void
    {
        self::bootKernel();

        $metadata = $this->createTranslationClassMetadata([
            'uniq_book_translation_title' => ['fields' => ['title']],
        ]);

        $this->createListener()->loadClassMetadata(
            new LoadClassMetadataEventArgs($metadata, $this->getEntityManager()),
        );

        $uniqueConstraints = $metadata->table['uniqueConstraints'];

        $this->assertArrayHasKey('uniq_book_translation_title', $uniqueConstraints);
        $this->assertArrayHasKey('app_book_translation_uniq_trans', $uniqueConstraints);
        $this->assertSame(
            ['translatable_id', 'locale'],
            $uniqueConstraints['app_book_translation_uniq_trans']['columns'],
        );
    }

    public function testItAddsTheTranslatableUniqueConstraintWhenAnotherOneUsesTheLegacyColumnsFormat(): void
    {
        self::bootKernel();

        $metadata = $this->createTranslationClassMetadata([
            'uniq_book_translation_title' => ['columns' => ['title']],
        ]);

        $this->createListener()->loadClassMetadata(
            new LoadClassMetadataEventArgs($metadata, $this->getEntityManager()),
        );

        $uniqueConstraints = $metadata->table['uniqueConstraints'];

        $this->assertArrayHasKey('uniq_book_translation_title', $uniqueConstraints);
        $this->assertArrayHasKey('app_book_translation_uniq_trans', $uniqueConstraints);
        $this->assertSame(
            ['translatable_id', 'locale'],
            $uniqueConstraints['app_book_translation_uniq_trans']['columns'],
        );
    }

    /**
     * @param array<string, array{columns?: array<string>, fields?: array<string>}> $uniqueConstraints
     */
    private function createTranslationClassMetadata(array $uniqueConstraints): ClassMetadata
    {
        $metadata = new ClassMetadata(BookTranslation::class, new DefaultNamingStrategy());
        $metadata->wakeupReflection(new RuntimeReflectionService());
        $metadata->setPrimaryTable([
            'name' => 'app_book_translation',
            'uniqueConstraints' => $uniqueConstraints,
        ]);

        return $metadata;
    }

    private function createListener(): ORMTranslatableListener
    {
        return self::getContainer()->get(ORMTranslatableListener::class);
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }
}
