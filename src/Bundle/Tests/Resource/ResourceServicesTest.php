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

namespace Sylius\Bundle\ResourceBundle\Tests\Resource;

use App\Entity\Book;
use App\Entity\ComicBook;
use App\Repository\BookRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ResourceServicesTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_allows_to_access_resource_services_from_container(): void
    {
        $client = parent::createClient();

        $repository = $client->getContainer()->get('app.repository.book');
        $this->assertTrue($repository instanceof RepositoryInterface);

        $repository = $client->getContainer()->get('app.repository.comic_book');
        $this->assertTrue($repository instanceof ServiceEntityRepository);

        $repository = $client->getContainer()->get('app.manager.book');
        $this->assertTrue($repository instanceof EntityManager);

        $repository = $client->getContainer()->get('app.controller.book');
        $this->assertTrue($repository instanceof ResourceController);

        $repository = $client->getContainer()->get('app.factory.book');
        $this->assertTrue($repository instanceof FactoryInterface);
    }

    /**
     * @test
     */
    public function it_will_return_the_same_repository_instance()
    {
        $client = parent::createClient();
        $repository = self::$container->get(BookRepository::class);

        $repositoryAlias = $client->getContainer()->get('app.repository.book');
        $this->assertSame($repository, $repositoryAlias);

        $em = $client->getContainer()->get('app.manager.book');
        $repositoryAlias = $em->getRepository(Book::class);
        $this->assertSame($repository, $repositoryAlias);

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $repositoryAlias = $em->getRepository(Book::class);
        $this->assertSame($repository, $repositoryAlias);
    }

    /**
     * @test
     */
    public function it_will_return_the_same_repository_instance_for_default_repositories()
    {
        $client = parent::createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $repository = $client->getContainer()->get('app.repository.comic_book');
        $repositoryAlias = $em->getRepository(ComicBook::class);

        $this->assertInstanceOf(RepositoryInterface::class, $repository);
        $this->assertSame($repository, $repositoryAlias);
    }
}
