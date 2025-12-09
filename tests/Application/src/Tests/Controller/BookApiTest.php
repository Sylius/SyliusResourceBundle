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

use App\Entity\BookTranslation;
use App\Foundry\Factory\BookFactory;
use App\Foundry\Factory\BookTranslationFactory;
use App\Foundry\Story\DefaultBooksStory;
use App\Foundry\Story\MoreBooksStory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BookApiTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "title":"Star Wars: Dark Disciple", 
                "author":"Christie Golden"
            }
            JSON
        );
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $book->_refresh();

        $enUsTranslation = $book->getTranslation('en_US');
        $plPLTranslation = $book->getTranslation('pl_PL');
        $this->assertInstanceOf(BookTranslation::class, $enUsTranslation);
        $this->assertInstanceOf(BookTranslation::class, $plPLTranslation);
        $this->assertEquals('Star Wars: Dark Disciple', $enUsTranslation->getTitle());
        $this->assertEquals('Gwiezdne Wojny: Mroczny Uczeń', $plPLTranslation->getTitle());
        $this->assertEquals('Christie Golden', $book->getAuthor());
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $book->_refresh();

        $this->assertEquals('Christie Golden', $book->getAuthor());
    }

    #[Test]
    public function it_allows_removing_a_book(): void
    {
        $book = BookFactory::createOne();
        $bookId = $book->getId();

        $this->client->request('DELETE', '/books/' . $book->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $this->assertEquals(0, $this->getContainer()->get('app.repository.book')->count([]));
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "title":"Lord of The Rings", 
                "author":"J.R.R. Tolkien"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_indexing_books(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultBooksStory::load();

        $this->client->request('GET', '/books/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesDefaultBooksIndex();
    }

    #[Test]
    public function it_allows_paginating_the_index_of_books(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/books/', ['page' => 2]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesMoreBooksIndexPage2();
    }

    #[Test]
    public function it_does_not_allow_showing_resource_if_it_not_exists(): void
    {
        $this->markAsSkippedIfNecessary();

        $this->client->request('GET', '/books/3');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_does_not_apply_sorting_for_non_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/sortable-books/', ['sorting' => ['name' => 'DESC']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesMoreBooksIndex();
    }

    #[Test]
    public function it_does_not_apply_filtering_for_non_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/filterable-books/', ['criteria' => ['name' => 'John']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesMoreBooksIndex();
    }

    #[Test]
    public function it_applies_sorting_for_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();

        $this->client->request('GET', '/sortable-books/', ['sorting' => ['id' => 'DESC']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 1,
                "limit": 10,
                "pages": 3,
                "total": 22,
                 "_links": {
                    "first": {
                        "href": "/sortable-books/?sorting%5Bid%5D=DESC&page=1&limit=10"
                    },
                    "last": {
                        "href": "/sortable-books/?sorting%5Bid%5D=DESC&page=3&limit=10"
                    },
                    "next": {
                        "href": "/sortable-books/?sorting%5Bid%5D=DESC&page=2&limit=10"
                    },
                    "self": {
                        "href": "/sortable-books/?sorting%5Bid%5D=DESC&page=1&limit=10"
                    }
                },
                "_embedded": {
                    "items": [
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 22"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 21"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 20"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 19"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 18"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 17"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 16"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 15"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 14"
                        },
                        {
                            "author": "@string@",
                            "id": @integer@,
                            "title": "Book 13"
                        }
                    ]
                }
            }
            JSON
        );
    }

    #[Test]
    public function it_applies_filtering_for_existing_field(): void
    {
        $this->markAsSkippedIfNecessary();

        MoreBooksStory::load();
        BookFactory::new()->withAuthor('J.R.R. Tolkien')->create();

        $this->client->request('GET', '/filterable-books/', ['criteria' => ['author' => 'J.R.R. Tolkien']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 1,
                "limit": 10,
                "pages": 1,
                "total": 1,
                "_links": {
                    "self": {
                        "href": "\/filterable-books\/?criteria%5Bauthor%5D=J.R.R.%20Tolkien&page=1&limit=10"
                    },
                    "first": {
                        "href": "\/filterable-books\/?criteria%5Bauthor%5D=J.R.R.%20Tolkien&page=1&limit=10"
                    },
                    "last": {
                        "href": "\/filterable-books\/?criteria%5Bauthor%5D=J.R.R.%20Tolkien&page=1&limit=10"
                    }
                },
                "_embedded": {
                    "items": [
                        {
                            "id": @integer@,
                            "author":  "J.R.R. Tolkien"
                        }
                    ]
                }
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_creating_a_book_via_custom_factory(): void
    {
        $this->markAsSkippedIfNecessary();

        $data =
            <<<'JSON'
            {
                "translations": {
                    "en_US": {
                        "title": "Star Wars: Dark Disciple"
                    }
                },
                "author": "Christie Golden"
            }
            JSON
        ;

        $this->client->request('POST', '/create-custom-book', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "title":"Star Wars: Dark Disciple", 
                "author":"Christie Golden"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_indexing_books_via_custom_repository(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultBooksStory::load();

        $this->client->request('GET', '/find-custom-books');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesDefaultBooksIndex();
    }

    #[Test]
    public function it_allows_showing_a_book_via_custom_repository(): void
    {
        $this->markAsSkippedIfNecessary();

        DefaultBooksStory::load();

        $this->client->request('GET', '/find-custom-book');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "title":"Lord of The Rings", 
                "author":"J.R.R. Tolkien"
            }
            JSON
        );
    }

    private function assertResponseMatchesDefaultBooksIndex(): void
    {
        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 1,
                "limit": 10,
                "pages": 1,
                "total": 2,
                "_links": {
                    "self": {
                        "href": "@string@"
                    },
                    "first": {
                        "href": "@string@"
                    },
                    "last": {
                        "href": "@string@"
                    }
                },
                "_embedded": {
                    "items": [
                        {
                            "id": @integer@,
                            "title": "Lord of The Rings",
                            "author": "J.R.R. Tolkien"
                        },
                        {
                            "id": @integer@,
                            "title": "Game of Thrones",
                            "author": "George R. R. Martin"
                        }
                    ]
                }
            }
            JSON
        );
    }

    private function assertResponseMatchesMoreBooksIndex(): void
    {
        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 1,
                "limit": 10,
                "pages": 3,
                "total": 22,
                "_links": {
                    "self": {
                        "href": "@string@"
                    },
                    "first": {
                        "href": "@string@"
                    },
                    "last": {
                        "href": "@string@"
                    },
                    "next": {
                        "href": "@string@"
                    }
                },
                "_embedded": {
                    "items": [
                        {
                            "id": @integer@,
                            "title": "Book 1",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 2",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 3",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 4",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 5",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 6",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 7",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 8",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 9",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 10",
                            "author": "@string@"
                        }
                    ]
                }
            }
            JSON
        );
    }

    private function assertResponseMatchesMoreBooksIndexPage2(): void
    {
        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "page": 2,
                "limit": 10,
                "pages": 3,
                "total": 22,
                "_links": {
                    "self": {
                        "href": "@string@"
                    },
                    "first": {
                        "href": "@string@"
                    },
                    "last": {
                        "href": "@string@"
                    },
                    "next": {
                        "href": "@string@"
                    },
                    "previous": {
                        "href": "@string@"
                    }
                },
                "_embedded": {
                    "items": [
                        {
                            "id": @integer@,
                            "title": "Book 11",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 12",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 13",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 14",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 15",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 16",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 17",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 18",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 19",
                            "author": "@string@"
                        },
                        {
                            "id": @integer@,
                            "title": "Book 20",
                            "author": "@string@"
                        }
                    ]
                }
            }
            JSON
        );
    }

    private function markAsSkippedIfNecessary(): void
    {
        if ('test_without_hateoas' === self::getContainer()->get('kernel')->getEnvironment()) {
            $this->markTestSkipped();
        }
    }

    public function assert()
    {
        return $this->assertEquals();
    }
}
