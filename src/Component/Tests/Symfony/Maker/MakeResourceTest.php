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

namespace Sylius\Component\Resource\Tests\Symfony\Maker;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class MakeResourceTest extends MakerTestCase
{
    private const BOOK_RESOURCE_PATH = 'tmp/Sylius/Resource/BookResource.php';

    private const DUMMY_RESOURCE_PATH = 'tmp/Entity/Dummy.php';

    private const DUMMY_REPOSITORY_PATH = 'src/Repository/Entity/DummyRepository.php';

    private const EXISTING_DUMMY_RESOURCE_PATH = 'tmp/Entity/ExistingDummy.php';

    private const EXISTING_DUMMY_REPOSITORY_PATH = 'src/Repository/Entity/ExistingDummyRepository.php';

    /** @test */
    public function it_can_create_resources(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:resource'));

        $this->assertFileDoesNotExist(self::file(self::BOOK_RESOURCE_PATH));

        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Sylius\\Resource\\BookResource', '--is-entity' => false]);

        $this->assertFileExists(self::file(self::BOOK_RESOURCE_PATH));
        $this->assertSame(self::getBookResourceExpectedContent(), \file_get_contents(self::file(self::BOOK_RESOURCE_PATH)));
    }

    /** @test */
    public function it_can_create_resource_entities(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:resource'));

        $this->assertFileDoesNotExist(self::file(self::DUMMY_RESOURCE_PATH));
        $this->assertFileDoesNotExist(self::file(self::DUMMY_REPOSITORY_PATH));

        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Entity\\Dummy', '--is-entity' => true]);

        $this->assertFileExists(self::file(self::DUMMY_RESOURCE_PATH));
        $this->assertSame(self::getDummyResourceExpectedContent(), \file_get_contents(self::file(self::DUMMY_RESOURCE_PATH)));

        $this->assertFileExists(self::file(self::DUMMY_REPOSITORY_PATH));
        $this->assertSame(self::getDummyRepositoryExpectedContent(), \file_get_contents(self::file(self::DUMMY_REPOSITORY_PATH)));
    }

    /** @test */
    public function it_can_update_entities(): void
    {
        $this->assertFileDoesNotExist(self::file(self::EXISTING_DUMMY_RESOURCE_PATH));
        $this->assertFileDoesNotExist(self::file(self::EXISTING_DUMMY_REPOSITORY_PATH));

        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:entity'));
        $tester->setInputs(['name', 'string', 255, 'y', '']);
        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Entity\\ExistingDummy']);

        $this->assertFileExists(self::file(self::EXISTING_DUMMY_RESOURCE_PATH));
        $this->assertFileExists(self::file(self::EXISTING_DUMMY_REPOSITORY_PATH));

        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:resource'));
        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Entity\\ExistingDummy', '--is-entity' => true]);

        $this->assertSame(self::getUpdatedDummyResourceExpectedContent(), \file_get_contents(self::file(self::EXISTING_DUMMY_RESOURCE_PATH)));
        $this->assertSame(self::getUpdatedDummyRepositoryExpectedContent(), \file_get_contents(self::file(self::EXISTING_DUMMY_REPOSITORY_PATH)));
    }

    private static function getBookResourceExpectedContent(): string
    {
        return <<<EOF
            <?php
            
            namespace App\Tests\Tmp\Sylius\Resource;
            
            use Sylius\Component\Resource\Metadata\Create;
            use Sylius\Component\Resource\Metadata\Delete;
            use Sylius\Component\Resource\Metadata\Index;
            use Sylius\Component\Resource\Metadata\Resource;
            use Sylius\Component\Resource\Metadata\Show;
            use Sylius\Component\Resource\Metadata\Update;
            use Sylius\Component\Resource\Model\ResourceInterface;
            use Symfony\Component\Uid\AbstractUid;
            
            #[Resource]
            #[Index(
                // grid: BookGrid::class,
            )]
            #[Create(
                // processor: CreateBookProcessor::class,
            )]
            #[Update(
                // provider: BookItemProvider::class,
                // processor: UpdateBookProcessor::class,
            )]
            #[Delete(
                // provider: BookItemProvider::class,
                // processor: DeleteBookProcessor::class,
            )]
            #[Show(
                // template: 'book/show.html.twig',
                // provider: BookItemProvider::class,
            )]
            final class BookResource implements ResourceInterface
            {
                public function __construct(
                    public ?AbstractUid \$id = null,
                ) {
                }
            
                public function getId(): ?string
                {
                    return \$this->id?->__toString();
                }
            }
            
            EOF
        ;
    }

    private static function getDummyResourceExpectedContent(): string
    {
        return <<<EOF
            <?php
            
            namespace App\Tests\Tmp\Entity;
            
            use App\Repository\Entity\DummyRepository;
            use Doctrine\ORM\Mapping as ORM;
            use Sylius\Component\Resource\Metadata\Resource;
            use Sylius\Component\Resource\Model\ResourceInterface;
            
            #[ORM\Entity(repositoryClass: DummyRepository::class)]
            #[Resource]
            class Dummy implements ResourceInterface
            {
                #[ORM\Id]
                #[ORM\GeneratedValue]
                #[ORM\Column]
                private ?int \$id = null;
            
                public function getId(): ?int
                {
                    return \$this->id;
                }
            }
            
            EOF
        ;
    }

    private static function getDummyRepositoryExpectedContent(): string
    {
        return <<<EOF
            <?php

            namespace App\Repository\Entity;
            
            use App\Tests\Tmp\Entity\Dummy;
            use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
            use Doctrine\Persistence\ManagerRegistry;
            use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
            use Sylius\Component\Resource\Repository\RepositoryInterface;
            
            /**
             * @extends ServiceEntityRepository<Dummy>
             *
             * @method Dummy|null find(\$id, \$lockMode = null, \$lockVersion = null)
             * @method Dummy|null findOneBy(array \$criteria, array \$orderBy = null)
             * @method Dummy[]    findAll()
             * @method Dummy[]    findBy(array \$criteria, array \$orderBy = null, \$limit = null, \$offset = null)
             */
            class DummyRepository extends ServiceEntityRepository implements RepositoryInterface
            {
                use ResourceRepositoryTrait;
            
                public function __construct(ManagerRegistry \$registry)
                {
                    parent::__construct(\$registry, Dummy::class);
                }
            }
            
            EOF
        ;
    }

    private static function getUpdatedDummyResourceExpectedContent(): string
    {
        return <<<EOF
            <?php

            namespace App\Tests\Tmp\Entity;
            
            use App\Repository\Entity\ExistingDummyRepository;
            use Doctrine\ORM\Mapping as ORM;
            use Sylius\Component\Resource\Metadata\Resource;
            use Sylius\Component\Resource\Model\ResourceInterface;
            
            #[ORM\Entity(repositoryClass: ExistingDummyRepository::class)]
            #[Resource]
            class ExistingDummy implements ResourceInterface
            {
                #[ORM\Id]
                #[ORM\GeneratedValue]
                #[ORM\Column]
                private ?int \$id = null;
            
                #[ORM\Column(length: 255, nullable: true)]
                private ?string \$name = null;
            
                public function getId(): ?int
                {
                    return \$this->id;
                }
            
                public function getName(): ?string
                {
                    return \$this->name;
                }
            
                public function setName(?string \$name): static
                {
                    \$this->name = \$name;
            
                    return \$this;
                }
            }
            
            EOF
        ;
    }

    private static function getUpdatedDummyRepositoryExpectedContent(): string
    {
        return <<<EOF
            <?php
            
            namespace App\Repository\Entity;
            
            use App\Tests\Tmp\Entity\ExistingDummy;
            use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
            use Doctrine\Persistence\ManagerRegistry;
            use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
            use Sylius\Component\Resource\Model\ResourceInterface;
            use Sylius\Component\Resource\Repository\RepositoryInterface;
            
            /**
             * @extends ServiceEntityRepository<ExistingDummy>
             *
             * @method ExistingDummy|null find(\$id, \$lockMode = null, \$lockVersion = null)
             * @method ExistingDummy|null findOneBy(array \$criteria, array \$orderBy = null)
             * @method ExistingDummy[]    findAll()
             * @method ExistingDummy[]    findBy(array \$criteria, array \$orderBy = null, \$limit = null, \$offset = null)
             */
            class ExistingDummyRepository extends ServiceEntityRepository implements RepositoryInterface
            {
                use ResourceRepositoryTrait;
            
                public function __construct(ManagerRegistry \$registry)
                {
                    parent::__construct(\$registry, ExistingDummy::class);
                }
            
                public function save(ExistingDummy \$entity, bool \$flush = false): void
                {
                    \$this->getEntityManager()->persist(\$entity);
            
                    if (\$flush) {
                        \$this->getEntityManager()->flush();
                    }
                }
            
                // TODO: You could remove this, cause this is already on the ResourceRepositoryTrait
                public function remove(ResourceInterface \$resource): void
                {
                    if (null !== \$this->find(\$resource->getId())) {
                        \$this->_em->remove(\$resource);
                        \$this->_em->flush();
                    }
                }
            
            //    /**
            //     * @return ExistingDummy[] Returns an array of ExistingDummy objects
            //     */
            //    public function findByExampleField(\$value): array
            //    {
            //        return \$this->createQueryBuilder('e')
            //            ->andWhere('e.exampleField = :val')
            //            ->setParameter('val', \$value)
            //            ->orderBy('e.id', 'ASC')
            //            ->setMaxResults(10)
            //            ->getQuery()
            //            ->getResult()
            //        ;
            //    }
            
            //    public function findOneBySomeField(\$value): ?ExistingDummy
            //    {
            //        return \$this->createQueryBuilder('e')
            //            ->andWhere('e.exampleField = :val')
            //            ->setParameter('val', \$value)
            //            ->getQuery()
            //            ->getOneOrNullResult()
            //        ;
            //    }
            }
            
            EOF
        ;
    }

    /**
     * @before
     *
     * @after
     */
    protected function removeGeneratedFiles(): void
    {
        $this->removeFile(self::file(self::BOOK_RESOURCE_PATH));
        $this->removeFile(self::file(self::DUMMY_RESOURCE_PATH));
        $this->removeFile(self::file(self::DUMMY_REPOSITORY_PATH));
        $this->removeFile(self::file(self::EXISTING_DUMMY_RESOURCE_PATH));
        $this->removeFile(self::file(self::EXISTING_DUMMY_REPOSITORY_PATH));
    }
}
