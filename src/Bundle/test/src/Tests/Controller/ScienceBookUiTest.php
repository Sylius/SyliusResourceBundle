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
            sprintf('<tr><td>%d</td><td>A Brief History of Time</td><td>Stephen Hawking</td></tr>', $scienceBooks['science-book1']->getId()),
            $content
        );
        $this->assertStringContainsString(
            sprintf('<tr><td>%d</td><td>The Future of Humanity</td><td>Michio Kaku</td></tr>', $scienceBooks['science-book2']->getId()),
            $content
        );
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
