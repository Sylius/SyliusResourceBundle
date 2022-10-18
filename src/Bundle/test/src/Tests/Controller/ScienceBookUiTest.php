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

namespace App\Tests\Controller;

use ApiTestCase\ApiTestCase;
use App\Entity\ScienceBook;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;

final class ScienceBookUiTest extends ApiTestCase
{
    /** @test */
    public function it_allows_showing_a_book(): void
    {
        $scienceBooks = $this->loadFixturesFromFile('science_books.yml');

        $this->client->request('GET', '/science-books/' . $scienceBooks['science-book1']->getId());
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString(sprintf('ID: %d', $scienceBooks['science-book1']->getId()), $content);
        $this->assertStringContainsString('Title: A Brief History of Time', $content);
        $this->assertStringContainsString('Author: Stephen Hawking', $content);
    }

    /** @test */
    public function it_allows_indexing_books(): void
    {
        $scienceBooks = $this->loadFixturesFromFile('science_books.yml');

        $this->client->request('GET', '/science-books/');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString('<h1>Books</h1>', $content);
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>A Brief History of Time</td><td>Stephen Hawking</td>', $scienceBooks['science-book1']->getId()),
            $content,
        );
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>The Future of Humanity</td><td>Michio Kaku</td>', $scienceBooks['science-book2']->getId()),
            $content,
        );
    }

    /** @test */
    public function it_allows_creating_a_book(): void
    {
        $newBookTitle = 'The Book of Why';
        $newBookAuthorFirstName = 'Judea';
        $newBookAuthorLastName = 'Pearl';

        $this->loadFixturesFromFile('science_books.yml');

        $this->client->request('GET', '/science-books/new');
        $this->client->submitForm('Create', [
            'sylius_resource[title]' => $newBookTitle,
            'sylius_resource[author][firstName]' => $newBookAuthorFirstName,
            'sylius_resource[author][lastName]' => $newBookAuthorLastName,
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var ScienceBook $book */
        $book = static::getContainer()->get('app.repository.science_book')->findOneBy(['title' => $newBookTitle]);

        $this->assertNotNull($book);
        $this->assertSame($newBookTitle, $book->getTitle());
        $this->assertSame($newBookAuthorFirstName, $book->getAuthorFirstName());
        $this->assertSame($newBookAuthorLastName, $book->getAuthorLastName());
    }

    /** @test */
    public function it_allows_updating_a_book(): void
    {
        $newBookTitle = 'The Book of Why';
        $newBookAuthorFirstName = 'Judea';
        $newBookAuthorLastName = 'Pearl';

        $scienceBooks = $this->loadFixturesFromFile('science_books.yml');

        $this->client->request('GET', '/science-books/' . $scienceBooks['science-book1']->getId() . '/edit');
        $this->client->submitForm('Save changes', [
            'sylius_resource[title]' => $newBookTitle,
            'sylius_resource[author][firstName]' => $newBookAuthorFirstName,
            'sylius_resource[author][lastName]' => $newBookAuthorLastName,
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var ScienceBook $book */
        $book = static::getContainer()->get('app.repository.science_book')->findOneBy(['title' => $newBookTitle]);

        $this->assertNotNull($book);
        $this->assertSame($newBookTitle, $book->getTitle());
        $this->assertSame($newBookAuthorFirstName, $book->getAuthorFirstName());
        $this->assertSame($newBookAuthorLastName, $book->getAuthorLastName());
    }

    /** @test */
    public function it_allows_deleting_a_book(): void
    {
        $this->loadFixturesFromFile('single_science_book.yml');

        $this->client->request('GET', '/science-books/');
        $this->client->submitForm('Delete');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var ScienceBook[] $books */
        $books = static::getContainer()->get('app.repository.science_book')->findAll();

        $this->assertEmpty($books);
    }

    /** @test */
    public function it_allows_filtering_books(): void
    {
        $scienceBooks = $this->loadFixturesFromFile('science_books.yml');

        $this->client->request('GET', '/science-books/?criteria[search][value]=history of time');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString('<h1>Books</h1>', $content);
        $this->assertStringContainsString(
            sprintf('<td>%d</td><td>A Brief History of Time</td><td>Stephen Hawking</td>', $scienceBooks['science-book1']->getId()),
            $content,
        );
        $this->assertStringNotContainsString(
            sprintf('<td>%d</td><td>The Future of Humanity</td><td>Michio Kaku</td>', $scienceBooks['science-book2']->getId()),
            $content,
        );
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
