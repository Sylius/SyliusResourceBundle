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

namespace App\Tests\Controller;

use App\Entity\ScienceBook;
use App\Foundry\Factory\AuthorFactory;
use App\Foundry\Factory\ScienceBookFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ScienceBookUiTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    #[Test]
    public function it_allows_showing_a_book(): void
    {
        $scienceBook = ScienceBookFactory::new()
            ->withTitle('A Brief History of Time')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Stephen')
                    ->withLastName('Hawking'),
            )
            ->create()
        ;

        $this->client->request('GET', '/science-books/' . $scienceBook->getId());
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $content = $response->getContent();
        $this->assertStringContainsString(sprintf('ID: %d', $scienceBook->getId()), $content);
        $this->assertStringContainsString('Title: A Brief History of Time', $content);
        $this->assertStringContainsString('Author: Stephen Hawking', $content);
    }

    #[Test]
    public function it_allows_indexing_books(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();

        $firstBook = ScienceBookFactory::new()
            ->withTitle('A Brief History of Time')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Stephen')
                    ->withLastName('Hawking'),
            )
            ->create()
        ;

        $secondBook = ScienceBookFactory::new()
            ->withTitle('The Future of Humanity')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Michio')
                    ->withLastName('Kaku'),
            )
            ->create()
        ;

        $this->client->request('GET', '/science-books');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $content = $response->getContent();
        $this->assertStringContainsString('<h1>Books</h1>', $content);
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>A Brief History of Time</td><td>Stephen Hawking</td>', $firstBook->getId()),
            $content,
        );
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>The Future of Humanity</td><td>Michio Kaku</td>', $secondBook->getId()),
            $content,
        );
    }

    #[Test]
    public function it_allows_indexing_books_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();

        $firstBook = ScienceBookFactory::new()
            ->withTitle('A Brief History of Time')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Stephen')
                    ->withLastName('Hawking'),
            )
            ->create()
        ;

        $secondBook = ScienceBookFactory::new()
            ->withTitle('The Future of Humanity')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Michio')
                    ->withLastName('Kaku'),
            )
            ->create()
        ;

        $this->client->request('GET', '/science-books/');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $content = $response->getContent();
        $this->assertStringContainsString('<h1>Books</h1>', $content);
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>A Brief History of Time</td><td>Stephen Hawking</td>', $firstBook->getId()),
            $content,
        );
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>The Future of Humanity</td><td>Michio Kaku</td>', $secondBook->getId()),
            $content,
        );
    }

    #[Test]
    public function it_allows_creating_a_book(): void
    {
        $newBookTitle = 'The Book of Why';
        $newBookAuthorFirstName = 'Judea';
        $newBookAuthorLastName = 'Pearl';

        $this->client->request('GET', '/science-books/new');
        $this->client->submitForm('Create', [
            'science_book[title]' => $newBookTitle,
            'science_book[author][firstName]' => $newBookAuthorFirstName,
            'science_book[author][lastName]' => $newBookAuthorLastName,
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var ScienceBook $book */
        $book = static::getContainer()->get('app.repository.science_book')->findOneBy(['title' => $newBookTitle]);

        $this->assertNotNull($book);
        $this->assertSame($newBookTitle, $book->getTitle());
        $this->assertSame($newBookAuthorFirstName, $book->getAuthorFirstName());
        $this->assertSame($newBookAuthorLastName, $book->getAuthorLastName());
    }

    #[Test]
    public function it_does_not_allow_to_create_a_book_if_there_is_a_validation_error(): void
    {
        $newBookTitle = 'The Book of Why';
        $newBookAuthorLastName = 'Pearl';

        $this->client->request('GET', '/science-books/new');
        $this->client->submitForm('Create', [
            'science_book[title]' => $newBookTitle,
            'science_book[author][lastName]' => $newBookAuthorLastName,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        /** @var ScienceBook $book */
        $book = static::getContainer()->get('app.repository.science_book')->findOneBy(['title' => $newBookTitle]);

        $this->assertNull($book);
    }

    #[Test]
    public function it_allows_updating_a_book(): void
    {
        $scienceBook = ScienceBookFactory::createOne();

        $newBookTitle = 'The Book of Why';
        $newBookAuthorFirstName = 'Judea';
        $newBookAuthorLastName = 'Pearl';

        $this->client->request('GET', '/science-books/' . $scienceBook->getId() . '/edit');
        $this->client->submitForm('Save changes', [
            'science_book[title]' => $newBookTitle,
            'science_book[author][firstName]' => $newBookAuthorFirstName,
            'science_book[author][lastName]' => $newBookAuthorLastName,
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        $scienceBook->_refresh();
        $this->assertSame($newBookTitle, $scienceBook->getTitle());
        $this->assertSame($newBookAuthorFirstName, $scienceBook->getAuthorFirstName());
        $this->assertSame($newBookAuthorLastName, $scienceBook->getAuthorLastName());
    }

    #[Test]
    public function it_does_not_allow_to_update_a_book_if_there_is_a_validation_error(): void
    {
        $scienceBook = ScienceBookFactory::new()
            ->withTitle('The Shinning')
        ->create();

        $newBookTitle = 'The Book of Why';
        $newBookAuthorLastName = 'Pearl';

        $this->client->request('GET', '/science-books/' . $scienceBook->getId() . '/edit');
        $this->client->submitForm('Save changes', [
            'science_book[title]' => $newBookTitle,
            'science_book[author][firstName]' => null,
            'science_book[author][lastName]' => $newBookAuthorLastName,
        ]);

        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_UNPROCESSABLE_ENTITY);

        /** @var ScienceBook $book */
        $book = static::getContainer()->get('app.repository.science_book')->find($scienceBook->getId());
        $this->getContainer()->get('doctrine.orm.entity_manager')->refresh($book);

        $this->assertNotEquals($newBookTitle, $book->getTitle());
    }

    #[Test]
    public function it_allows_deleting_a_book(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();

        ScienceBookFactory::createOne();

        $this->client->request('GET', '/science-books');
        $this->client->submitForm('Delete');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var ScienceBook[] $books */
        $books = static::getContainer()->get('app.repository.science_book')->findAll();

        $this->assertEmpty($books);
    }

    #[Test]
    public function it_allows_deleting_a_book_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();

        ScienceBookFactory::createOne();

        $this->client->request('GET', '/science-books/');
        $this->client->submitForm('Delete');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var ScienceBook[] $books */
        $books = static::getContainer()->get('app.repository.science_book')->findAll();

        $this->assertEmpty($books);
    }

    #[Test]
    public function it_allows_filtering_books(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();

        $firstBook = ScienceBookFactory::new()
            ->withTitle('A Brief History of Time')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Stephen')
                    ->withLastName('Hawking'),
            )
            ->create()
        ;

        $secondBook = ScienceBookFactory::new()
            ->withTitle('The Future of Humanity')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Michio')
                    ->withLastName('Kaku'),
            )
            ->create()
        ;

        $this->client->request('GET', '/science-books?criteria[search][value]=history of time');
        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString('<h1>Books</h1>', $content);
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>A Brief History of Time</td><td>Stephen Hawking</td>', $firstBook->getId()),
            $content,
        );
        $this->assertStringNotContainsString(
            sprintf('<td>%d</td><td>The Future of Humanity</td><td>Michio Kaku</td>', $secondBook->getId()),
            $content,
        );
    }

    #[Test]
    public function it_allows_filtering_books_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();

        $firstBook = ScienceBookFactory::new()
            ->withTitle('A Brief History of Time')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Stephen')
                    ->withLastName('Hawking'),
            )
            ->create()
        ;

        $secondBook = ScienceBookFactory::new()
            ->withTitle('The Future of Humanity')
            ->withAuthor(
                AuthorFactory::new()
                    ->withFirstName('Michio')
                    ->withLastName('Kaku'),
            )
            ->create()
        ;

        $this->client->request('GET', '/science-books/?criteria[search][value]=history of time');
        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString('<h1>Books</h1>', $content);
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>A Brief History of Time</td><td>Stephen Hawking</td>', $firstBook->getId()),
            $content,
        );
        $this->assertStringNotContainsString(
            sprintf('<td>%d</td><td>The Future of Humanity</td><td>Michio Kaku</td>', $secondBook->getId()),
            $content,
        );
    }

    private function isRoutingPathBcLayerEnabled(): bool
    {
        return (bool) $this->getContainer()->getParameter('sylius.routing_path_bc_layer');
    }

    private function markAsSkippedIfBcLayerIsEnabled(): void
    {
        if ($this->isRoutingPathBcLayerEnabled()) {
            $this->markTestSkipped('This test requires The BC layer to be disabled.');
        }
    }

    private function markAsSkippedIfBcLayerIsNotEnabled(): void
    {
        if (!$this->isRoutingPathBcLayerEnabled()) {
            $this->markTestSkipped('This test requires The BC layer to be enabled.');
        }
    }
}
