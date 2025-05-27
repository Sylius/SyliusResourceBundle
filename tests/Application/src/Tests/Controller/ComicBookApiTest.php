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
use App\Foundry\Factory\AuthorFactory;
use App\Foundry\Factory\ComicBookFactory;
use App\Foundry\Story\DefaultComicBooksStory;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

final class ComicBookApiTest extends JsonApiTestCase
{
    use Factories;

    /**
     * @test
     */
    public function it_allows_creating_a_comic_book(): void
    {
        $data =
<<<EOT
        {
            "title": "Deadpool #1-69",
            "author": {
                "firstName": "Joe",
                "lastName": "Kelly"
            }
        }
EOT;

        $this->client->request('POST', '/v1/comic-books/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'comic-books/create_response', Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function it_allows_versioned_creating_a_comic_book(): void
    {
        $this->markAsSkippedIfNecessary();

        $data =
<<<EOT
        {
            "title": "Deadpool #1-69",
            "author": {
                "firstName": "Joe",
                "lastName": "Kelly"
            }
        }
EOT;

        $this->client->request('POST', '/v1.2/comic-books/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'comic-books/versioned_create_response', Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function it_allows_updating_a_comic_book(): void
    {
        $comicBook = self::someComicBook()->create();

        $data =
<<<EOT
        {
            "title": "Deadpool #1-69",
            "author": {
                "firstName": "Joe",
                "lastName": "Kelly"
            }
        }
EOT;

        $this->client->request('PUT', '/v1/comic-books/' . $comicBook->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function it_allows_updating_partial_information_about_a_comic_book(): void
    {
        $comicBook = self::someComicBook()->create();

        $data =
 <<<EOT
        {
            "author": {
                "firstName": "Joe",
                "lastName": "Kelly"
            }
        }
EOT;

        $this->client->request('PATCH', '/v1/comic-books/' . $comicBook->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function it_allows_removing_a_comic_book(): void
    {
        $comicBook = self::someComicBook()->create();

        $this->client->request('DELETE', '/v1/comic-books/' . $comicBook->getId());
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function it_allows_showing_a_comic_book(): void
    {
        $comicBook = self::someComicBook()->create();

        $this->client->request('GET', '/v1/comic-books/' . $comicBook->getId());
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'comic-books/show_response');
    }

    /**
     * @test
     */
    public function it_allows_versioning_of_a_showing_comic_book_serialization(): void
    {
        $this->markAsSkippedIfNecessary();

        $comicBook = self::someComicBook()->create();

        $this->client->request('GET', '/v1.2/comic-books/' . $comicBook->getId());
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'comic-books/versioned_show_response');
    }

    /**
     * @test
     */
    public function it_allows_indexing_of_comic_books(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultComicBooksStory::load();

        $this->client->request('GET', '/v1/comic-books/');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'comic-books/index_response');
    }

    /**
     * @test
     */
    public function it_allows_versioned_indexing_of_comic_books(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultComicBooksStory::load();

        $this->client->request('GET', '/v1.2/comic-books/');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'comic-books/versioned_index_response');
    }

    /**
     * @test
     */
    public function it_does_not_allow_showing_resource_if_it_does_not_exist(): void
    {
        $this->client->request('GET', '/v1/comic-books/3');
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NOT_FOUND);
    }

    private function markAsSkippedIfNecessary(): void
    {
        if ('test_without_hateoas' === self::$sharedKernel->getEnvironment()) {
            $this->markTestSkipped();
        }
    }

    private static function someComicBook(): ComicBookFactory
    {
        return ComicBookFactory::new()
            ->withTitle('Old Man Logan')
            ->withAuthor(
                AuthorFactory::new()
                ->withFirstName('Andrea')
                ->withLastName('Sorrentino'),
            )
        ;
    }
}
