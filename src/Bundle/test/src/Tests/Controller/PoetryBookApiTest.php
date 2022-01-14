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

use ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class PoetryBookApiTest extends JsonApiTestCase
{
    /** @test */
    public function it_allows_creating_a_book(): void
    {
        $data =
<<<EOT
        {
            "title": "Romantyczność",
            "author": {
                "firstName": "Adam",
                "lastName": "Mickiewicz"
            }
        }
EOT;

        $this->client->request('POST', '/poetry-books/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'poetry-books/create_response', Response::HTTP_CREATED);
    }

    /** @test */
    public function it_allows_updating_a_poetry_book(): void
    {
        $objects = $this->loadFixturesFromFile('poetry_books.yml');

        $data =
<<<EOT
        {
            "title": "Poezje",
            "author": {
                "firstName": "Juliusz",
                "lastName": "Słowacki"
            }
        }
EOT;

        $this->client->request('PUT', '/poetry-books/' . $objects['poetry-book1']->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_allows_updating_partial_information_about_a_poetry_book(): void
    {
        $objects = $this->loadFixturesFromFile('poetry_books.yml');

        $data =
<<<EOT
        {
            "author": {
                "firstName": "A.",
                "lastName": "Mickiewicz"
            }
        }
EOT;

        $this->client->request('PATCH', '/poetry-books/' . $objects['poetry-book1']->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_allows_showing_a_poetry_book(): void
    {
        $objects = $this->loadFixturesFromFile('poetry_books.yml');

        $this->client->request('GET', '/poetry-books/' . $objects['poetry-book1']->getId());
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'poetry-books/show_response');
    }

    /** @test */
    public function it_allows_deleting_a_poetry_book(): void
    {
        $objects = $this->loadFixturesFromFile('poetry_books.yml');

        $this->client->request('DELETE', '/poetry-books/' . $objects['poetry-book1']->getId());
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);

        $this->client->request('GET', '/poetry-books/' . $objects['poetry-book1']->getId());
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_allows_indexing_of_poetry_books(): void
    {
        $this->loadFixturesFromFile('poetry_books.yml');

        $this->client->request('GET', '/poetry-books/');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'poetry-books/index_response');
    }

    /** @test */
    public function it_allows_changing_state_of_a_poetry_book(): void
    {
        $objects = $this->loadFixturesFromFile('poetry_books.yml');

        $this->client->request('PUT', sprintf('/poetry-books/%d/cancel', $objects['poetry-book1']->getId()), [], [], ['CONTENT_TYPE' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_OK);
        $this->assertResponse($response, 'poetry-books/show_cancelled_book_response');
    }
}
