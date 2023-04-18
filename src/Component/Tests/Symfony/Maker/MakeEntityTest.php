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
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

final class MakeEntityTest extends KernelTestCase
{
    private const BOOK_ENTITY_PATH = 'Book.php';

    private const BOOK_REPOSITORY_PATH = 'BookRepository.php';

    /** @test */
    public function it_can_create_entities(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel()))->find('make:entity'));

        $this->assertFileDoesNotExist(self::tempEntityFile(self::BOOK_ENTITY_PATH));
        $this->assertFileDoesNotExist(self::tempRepositoryFile(self::BOOK_REPOSITORY_PATH));

        $tester->setInputs([
            'title',
            'string',
            255,
        ]);
        $tester->execute(['name' => 'Tmp\Book', '--sylius-resource' => true]);

        $this->assertFileExists(self::tempEntityFile(self::BOOK_ENTITY_PATH));
        $this->assertSame(self::getBookResourceExpectedContent(), \file_get_contents(self::tempEntityFile(self::BOOK_ENTITY_PATH)));

        $this->assertFileExists(self::tempRepositoryFile(self::BOOK_REPOSITORY_PATH));
        $this->assertSame(self::getBookRepositoryExpectedContent(), \file_get_contents(self::tempRepositoryFile(self::BOOK_REPOSITORY_PATH)));
    }

    private static function getBookResourceExpectedContent(): string
    {
        return <<<EOF
            <?php

            namespace App\Entity\Tmp;
            
            use App\Repository\Tmp\BookRepository;
            use Doctrine\ORM\Mapping as ORM;
            use Sylius\Component\Resource\Metadata\Resource;
            use Sylius\Component\Resource\Model\ResourceInterface;
            
            #[ORM\Entity(repositoryClass: BookRepository::class)]
            #[Resource]
            class Book implements ResourceInterface
            {
                #[ORM\Id]
                #[ORM\GeneratedValue]
                #[ORM\Column]
                private ?int \$id = null;
            
                #[ORM\Column(length: 255)]
                private ?string \$title = null;
            
                public function getId(): ?int
                {
                    return \$this->id;
                }
            
                public function getTitle(): ?string
                {
                    return \$this->title;
                }
            
                public function setTitle(string \$title): self
                {
                    \$this->title = \$title;
            
                    return \$this;
                }
            }
            
            EOF
        ;
    }

    private static function getBookRepositoryExpectedContent(): string
    {
        return <<<EOF
            <?php

            namespace App\Repository\Tmp;
            
            use App\Entity\Tmp\Book;
            use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
            use Doctrine\Persistence\ManagerRegistry;
            use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
            use Sylius\Component\Resource\Model\ResourceInterface;
            use Sylius\Component\Resource\Repository\RepositoryInterface;
            
            /**
             * @extends ServiceEntityRepository<Book>
             *
             * @method Book|null find(\$id, \$lockMode = null, \$lockVersion = null)
             * @method Book|null findOneBy(array \$criteria, array \$orderBy = null)
             * @method Book[]    findAll()
             * @method Book[]    findBy(array \$criteria, array \$orderBy = null, \$limit = null, \$offset = null)
             */
            class BookRepository extends ServiceEntityRepository implements RepositoryInterface
            {
                use ResourceRepositoryTrait;
            
                public function __construct(ManagerRegistry \$registry)
                {
                    parent::__construct(\$registry, Book::class);
                }
            
                public function save(Book \$entity, bool \$flush = false): void
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
            //     * @return Book[] Returns an array of Book objects
            //     */
            //    public function findByExampleField(\$value): array
            //    {
            //        return \$this->createQueryBuilder('b')
            //            ->andWhere('b.exampleField = :val')
            //            ->setParameter('val', \$value)
            //            ->orderBy('b.id', 'ASC')
            //            ->setMaxResults(10)
            //            ->getQuery()
            //            ->getResult()
            //        ;
            //    }
            
            //    public function findOneBySomeField(\$value): ?Book
            //    {
            //        return \$this->createQueryBuilder('b')
            //            ->andWhere('b.exampleField = :val')
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
     * @after
     */
    public static function cleanupTmpDirs(): void
    {
        (new Filesystem())->remove(self::tempEntityDir());
        (new Filesystem())->remove(self::tempRepositoryDir());
    }

    private static function tempEntityDir(): string
    {
        return dirname(__DIR__, 4) . '/Bundle/test/src/Entity/Tmp';
    }

    private static function tempRepositoryDir(): string
    {
        return dirname(__DIR__, 4) . '/Bundle/test/src/Repository/Tmp';
    }

    protected static function tempEntityFile(string $path): string
    {
        return \sprintf('%s/%s', self::tempEntityDir(), $path);
    }

    protected static function tempRepositoryFile(string $path): string
    {
        return \sprintf('%s/%s', self::tempRepositoryDir(), $path);
    }
}
