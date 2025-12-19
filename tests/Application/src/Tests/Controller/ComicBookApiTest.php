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

use App\Foundry\Factory\AuthorFactory;
use App\Foundry\Factory\ComicBookFactory;
use App\Foundry\Story\DefaultComicBooksStory;
use Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle;
use FOS\RestBundle\FOSRestBundle;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ComicBookApiTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    protected function setUp(): void
    {
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();
    }

    #[Test]
    public function it_allows_creating_a_comic_book(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();

        $data =
            <<<'JSON'
            {
                "title": "Deadpool #1-69",
                "author": {
                    "firstName": "Joe",
                    "lastName": "Kelly"
                }
            }
            JSON
        ;

        $this->client->request('POST', '/v1/comic-books', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "author": {
                    "first_name": "Joe",
                    "last_name": "Kelly"
                },
                "title": "Deadpool #1-69"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_creating_a_comic_book_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();

        $data =
            <<<'JSON'
            {
                "title": "Deadpool #1-69",
                "author": {
                    "firstName": "Joe",
                    "lastName": "Kelly"
                }
            }
            JSON
        ;

        $this->client->request('POST', '/v1/comic-books/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "author": {
                    "first_name": "Joe",
                    "last_name": "Kelly"
                },
                "title": "Deadpool #1-69"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_versioned_creating_a_comic_book(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();
        $this->markAsSkippedIfHateoasIsNotAvailable();

        $data =
            <<<'JSON'
            {
                "title": "Deadpool #1-69",
                "author": {
                    "firstName": "Joe",
                    "lastName": "Kelly"
                }
            }
            JSON
        ;

        $this->client->request('POST', '/v1.2/comic-books', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "author_first_name": "Joe",
                "author_last_name": "Kelly",
                "title": "Deadpool #1-69"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_versioned_creating_a_comic_book_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();
        $this->markAsSkippedIfHateoasIsNotAvailable();

        $data =
            <<<'JSON'
            {
                "title": "Deadpool #1-69",
                "author": {
                    "firstName": "Joe",
                    "lastName": "Kelly"
                }
            }
            JSON
        ;

        $this->client->request('POST', '/v1.2/comic-books/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "author_first_name": "Joe",
                "author_last_name": "Kelly",
                "title": "Deadpool #1-69"
            }
            JSON
        );
    }

    #[Test]
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    #[Test]
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $book = $this->getContainer()->get('app.repository.comic_book')->find($comicBook->getId());
        $this->getContainer()->get('doctrine.orm.entity_manager')->refresh($book);

        $this->assertEquals('Joe', $book->getAuthor()->getFirstName());
        $this->assertEquals('Kelly', $book->getAuthor()->getLastName());
    }

    #[Test]
    public function it_allows_removing_a_comic_book(): void
    {
        $comicBook = self::someComicBook()->create();

        $this->client->request('DELETE', '/v1/comic-books/' . $comicBook->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_allows_showing_a_comic_book(): void
    {
        $comicBook = self::someComicBook()->create();

        $this->client->request('GET', '/v1/comic-books/' . $comicBook->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "author": {
                    "first_name": "Andrea",
                    "last_name": "Sorrentino"
                },
                "title": "Old Man Logan"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_versioning_of_a_showing_comic_book_serialization(): void
    {
        $this->markAsSkippedIfHateoasIsNotAvailable();

        $comicBook = self::someComicBook()->create();

        $this->client->request('GET', '/v1.2/comic-books/' . $comicBook->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "author_first_name": "Andrea",
                "author_last_name": "Sorrentino",
                "title": "Old Man Logan"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_indexing_of_comic_books(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();
        $this->markAsSkippedIfHateoasIsNotAvailable();

        DefaultComicBooksStory::load();

        $this->client->request('GET', '/v1/comic-books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 1,
                "limit": 10,
                "pages": 1,
                "total": 2,
                "_links": {
                    "self": {
                        "href": "\/v1\/comic-books?page=1&limit=10"
                    },
                    "first": {
                        "href": "\/v1\/comic-books?page=1&limit=10"
                    },
                    "last": {
                        "href": "\/v1\/comic-books?page=1&limit=10"
                    }
                },
                "_embedded": {
                    "items": [
                        {
                            "id": @integer@,
                            "author": {
                                "first_name": "Andrea",
                                "last_name": "Sorrentino"
                            },
                            "title": "Old Man Logan"
                        },
                        {
                            "id": @integer@,
                            "author": {
                                "first_name": "Brian Michael",
                                "last_name": "Bendis"
                            },
                            "title": "Civil War II"
                        }
                    ]
                }
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_indexing_of_comic_books_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();
        $this->markAsSkippedIfHateoasIsNotAvailable();

        DefaultComicBooksStory::load();

        $this->client->request('GET', '/v1/comic-books/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 1,
                "limit": 10,
                "pages": 1,
                "total": 2,
                "_links": {
                    "self": {
                        "href": "\/v1\/comic-books\/?page=1&limit=10"
                    },
                    "first": {
                        "href": "\/v1\/comic-books\/?page=1&limit=10"
                    },
                    "last": {
                        "href": "\/v1\/comic-books\/?page=1&limit=10"
                    }
                },
                "_embedded": {
                    "items": [
                        {
                            "id": @integer@,
                            "author": {
                                "first_name": "Andrea",
                                "last_name": "Sorrentino"
                            },
                            "title": "Old Man Logan"
                        },
                        {
                            "id": @integer@,
                            "author": {
                                "first_name": "Brian Michael",
                                "last_name": "Bendis"
                            },
                            "title": "Civil War II"
                        }
                    ]
                }
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_versioned_indexing_of_comic_books(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();
        $this->markAsSkippedIfHateoasIsNotAvailable();

        DefaultComicBooksStory::load();

        $this->client->request('GET', 'v1.2/comic-books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 1,
                "limit": 10,
                "pages": 1,
                "total": 2,
                "_links": {
                    "self": {
                        "href": "\/v1.2\/comic-books?page=1&limit=10"
                    },
                    "first": {
                        "href": "\/v1.2\/comic-books?page=1&limit=10"
                    },
                    "last": {
                        "href": "\/v1.2\/comic-books?page=1&limit=10"
                    }
                },
                "_embedded": {
                  "items": [
                    {
                      "id": @integer@,
                      "author_first_name": "Andrea",
                      "author_last_name": "Sorrentino",
                      "title": "Old Man Logan"
                    },
                    {
                      "id": @integer@,
                      "author_first_name": "Brian Michael",
                      "author_last_name": "Bendis",
                      "title": "Civil War II"
                    }
                  ]
                }
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_versioned_indexing_of_comic_books_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();
        $this->markAsSkippedIfHateoasIsNotAvailable();

        DefaultComicBooksStory::load();

        $this->client->request('GET', '/v1.2/comic-books/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<JSON
            {
                "page": 1,
                "limit": 10,
                "pages": 1,
                "total": 2,
                "_links": {
                    "self": {
                        "href": "\/v1.2\/comic-books\/?page=1&limit=10"
                    },
                    "first": {
                        "href": "\/v1.2\/comic-books\/?page=1&limit=10"
                    },
                    "last": {
                        "href": "\/v1.2\/comic-books\/?page=1&limit=10"
                    }
                },
                "_embedded": {
                  "items": [
                    {
                      "id": @integer@,
                      "author_first_name": "Andrea",
                      "author_last_name": "Sorrentino",
                      "title": "Old Man Logan"
                    },
                    {
                      "id": @integer@,
                      "author_first_name": "Brian Michael",
                      "author_last_name": "Bendis",
                      "title": "Civil War II"
                    }
                  ]
                }
            }
            JSON
        );
    }

    #[Test]
    public function it_does_not_allow_showing_resource_if_it_does_not_exist(): void
    {
        $this->client->request('GET', '/v1/comic-books/3');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
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

    private function markAsSkippedIfHateoasIsNotAvailable(): void
    {
        if (!class_exists(BazingaHateoasBundle::class)) {
            $this->markTestSkipped('HateoasBundle is not installed.');
        }
    }

    private function markAsSkippedIfFosRestBundleIsNotAvailable(): void
    {
        if (!class_exists(FOSRestBundle::class)) {
            $this->markTestSkipped('FriendsOfSymfony Rest Bundle is not installed.');
        }
    }
}
