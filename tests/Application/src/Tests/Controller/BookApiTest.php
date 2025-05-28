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

use ApiTestCase\JsonApiTestCase;
use App\Foundry\Factory\BookFactory;
use App\Foundry\Factory\BookTranslationFactory;
use App\Foundry\Story\DefaultBooksStory;
use App\Foundry\Story\MoreBooksStory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

class BookApiTest extends JsonApiTestCase
{
    use Factories;

    #[Test]
    public function it_allows_creating_a_book(): void
    {
        $this->markAsSkippedIfNecessary();

        $data =
<<<EOT
        {
            "translations": {
                "en_US": {
                    "title": "Star Wars: Dark Disciple"
                }
            },
            "author": "Christie Golden"
        }
EOT;

        $this->client->request('POST', '/books/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/create_response', Response::HTTP_CREATED);
    }

    #[Test]
    public function it_allows_updating_a_book(): void
    {
        $book = BookFactory::createOne();

        $data =
<<<EOT
        {
             "translations": {
                "en_US": {
                    "title": "Star Wars: Dark Disciple"
                },
                "pl_PL": {
                    "title": "Gwiezdne Wojny: Mroczny Uczeń"
                }
            },
            "author": "Christie Golden"
        }
EOT;

        $this->client->request('PUT', '/books/' . $book->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_allows_updating_partial_information_about_a_book(): void
    {
        $book = BookFactory::createOne();

        $data =
 <<<EOT
        {
            "author": "Christie Golden"
        }
EOT;

        $this->client->request('PATCH', '/books/' . $book->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_allows_removing_a_book(): void
    {
        $book = BookFactory::createOne();

        $this->client->request('DELETE', '/books/' . $book->getId());
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_allows_showing_a_book(): void
    {
        $this->markAsSkippedIfNecessary();

        $book = BookFactory::new()
            ->withTranslations([
                BookTranslationFactory::new()
                    ->withLocale('en_US')
                    ->withTitle('Lord of The Rings'),
                BookTranslationFactory::new()
                    ->withLocale('pl_PL')
                    ->withTitle('Władca Pierścieni'),
            ])
            ->withAuthor('J.R.R. Tolkien')
            ->create()
        ;

        $this->client->request('GET', '/books/' . $book->getId());
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/show_response');
    }

    #[Test]
    public function it_allows_indexing_books(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultBooksStory::load();

        $this->client->request('GET', '/books/');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/index_response');
    }

    #[Test]
    public function it_allows_paginating_the_index_of_books(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/books/', ['page' => 2]);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/paginated_index_response');
    }

    #[Test]
    public function it_does_not_allow_showing_resource_if_it_not_exists(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultBooksStory::load();

        $this->client->request('GET', '/books/3');
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_does_not_apply_sorting_for_un_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/sortable-books/', ['sorting' => ['name' => 'DESC']]);
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
    }

    #[Test]
    public function it_does_not_apply_filtering_for_un_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/filterable-books/', ['criteria' => ['name' => 'John']]);
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
    }

    #[Test]
    public function it_applies_sorting_for_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/sortable-books/', ['sorting' => ['id' => 'DESC']]);
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
    }

    #[Test]
    public function it_applies_filtering_for_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/filterable-books/', ['criteria' => ['author' => 'J.R.R. Tolkien']]);
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
    }

    #[Test]
    public function it_allows_creating_a_book_via_custom_factory(): void
    {
        $this->markAsSkippedIfNecessary();

        $data =
            <<<EOT
                    {
            "translations": {
                "en_US": {
                    "title": "Star Wars: Dark Disciple"
                }
            },
            "author": "Christie Golden"
        }
EOT;

        $this->client->request('POST', '/create-custom-book', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/create_response', Response::HTTP_CREATED);
    }

    #[Test]
    public function it_allows_indexing_books_via_custom_repository(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultBooksStory::load();

        $this->client->request('GET', '/find-custom-books');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/index_response');
    }

    #[Test]
    public function it_allows_showing_a_book_via_custom_repository(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultBooksStory::load();

        $this->client->request('GET', '/find-custom-book');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/show_response');
    }

    private function markAsSkippedIfNecessary(): void
    {
        if ('test_without_hateoas' === self::$sharedKernel->getEnvironment()) {
            $this->markTestSkipped();
        }
    }
}
