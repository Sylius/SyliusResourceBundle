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

namespace Sylius\Component\Resource\Tests\Symfony\Maker;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class MakeResourceTest extends MakerTestCase
{
    private const BOOK_RESOURCE_PATH = 'Tests/Tmp/Sylius/Resource/BookResource.php';

    private const DUMMY_RESOURCE_PATH = 'Tests/Tmp/Entity/Dummy.php';

    private const DUMMY_REPOSITORY_PATH = 'Repository/Tests/Tmp/Entity/DummyRepository.php';

    /** @test */
    public function it_can_create_resources(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:resource'));

        $this->removeFile(self::file(self::BOOK_RESOURCE_PATH));
        $this->assertFileDoesNotExist(self::file(self::BOOK_RESOURCE_PATH));

        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Sylius\\Resource\\BookResource', '--is-entity' => false]);

        $this->assertFileExists(self::file(self::BOOK_RESOURCE_PATH));
        $this->assertSame(self::getBookResourceExpectedContent(), \file_get_contents(self::file(self::BOOK_RESOURCE_PATH)));
    }

    /** @test */
    public function it_can_create_resource_entities(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:resource'));

        $this->removeFile(self::file(self::DUMMY_RESOURCE_PATH));
        $this->removeFile(self::file(self::DUMMY_REPOSITORY_PATH));

        $this->assertFileDoesNotExist(self::file(self::DUMMY_RESOURCE_PATH));

        $tester->execute(['name' => '\\App\\Tests\\Tmp\\Entity\\Dummy', '--is-entity' => true]);

        $this->assertFileExists(self::file(self::DUMMY_RESOURCE_PATH));
        $this->assertSame(self::getDummyResourceExpectedContent(), \file_get_contents(self::file(self::DUMMY_RESOURCE_PATH)));

        $this->assertFileExists(self::file(self::DUMMY_REPOSITORY_PATH));
        $this->assertSame(self::getDummyRepositoryExpectedContent(), \file_get_contents(self::file(self::DUMMY_REPOSITORY_PATH)));
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

            use App\Repository\Tests\Tmp\Entity\DummyRepository;
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

            namespace App\Repository\Tests\Tmp\Entity;
            
            use App\Tests\Tmp\Entity\Dummy;
            use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
            use Doctrine\Persistence\ManagerRegistry;
            use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
            use Sylius\Component\Resource\Model\ResourceInterface;
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
            
                public function save(Dummy \$entity, bool \$flush = false): void
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
            //     * @return Dummy[] Returns an array of Dummy objects
            //     */
            //    public function findByExampleField(\$value): array
            //    {
            //        return \$this->createQueryBuilder('d')
            //            ->andWhere('d.exampleField = :val')
            //            ->setParameter('val', \$value)
            //            ->orderBy('d.id', 'ASC')
            //            ->setMaxResults(10)
            //            ->getQuery()
            //            ->getResult()
            //        ;
            //    }
            
            //    public function findOneBySomeField(\$value): ?Dummy
            //    {
            //        return \$this->createQueryBuilder('d')
            //            ->andWhere('d.exampleField = :val')
            //            ->setParameter('val', \$value)
            //            ->getQuery()
            //            ->getOneOrNullResult()
            //        ;
            //    }
            }
            
            EOF
            ;
    }
}
