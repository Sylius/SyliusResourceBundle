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

use App\Kernel;
use App\Subscription\Foundry\Factory\SubscriptionFactory;
use App\Subscription\Foundry\Story\DefaultSubscriptionsStory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Tests\PurgeDatabaseTrait;
use Zenstruck\Foundry\Test\Factories;

final class SubscriptionJsonApiTest extends ApiTestCase
{
    use Factories;
    use PurgeDatabaseTrait;

    #[Test]
    public function it_allows_showing_a_subscription(): void
    {
        $subscription = SubscriptionFactory::new()
            ->withEmail('marty.mcfly@bttf.com')
            ->create()
        ;

        $this->client->request('GET', '/ajax/subscriptions/' . $subscription->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "state": "new",
                "email": "marty.mcfly@bttf.com"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_indexing_subscriptions(): void
    {
        DefaultSubscriptionsStory::load();

        $this->client->request('GET', '/ajax/subscriptions');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "items": [
                    {
                        "state": "new",
                        "email": "marty.mcfly@bttf.com"
                    },
                    {
                        "state": "new",
                        "email": "doc.brown@bttf.com"
                    },
                    {
                        "state": "accepted",
                        "email": "biff.tannen@bttf.com"
                    },
                    {
                        "state": "new",
                        "email": "lorraine.baines@bttf.com"
                    },
                    {
                        "state": "new",
                        "email": "george.mcfly@bttf.com"
                    },
                    {
                        "state": "new",
                        "email": "jennifer.parker@bttf.com"
                    }
                ],
                "pagination": {
                    "current_page": 1,
                    "has_previous_page": false,
                    "has_next_page": false,
                    "per_page": 10,
                    "total_items": 6,
                    "total_pages": 1
                }
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_creating_a_subscription(): void
    {
        $data =
            <<<'JSON'
            {
                "email": "marty.mcfly@bttf.com"
            }
            JSON
        ;

        $this->client->request('POST', '/ajax/subscriptions', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "state": "new",
                "email": "marty.mcfly@bttf.com"
            }
            JSON
        );
    }

    #[Test]
    public function it_does_not_allow_to_create_a_subscription_if_there_is_a_validation_error(): void
    {
        $data =
            <<<'JSON'
            {
                "email": ""
            }
            JSON
        ;

        $this->client->request('POST', '/ajax/subscriptions', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        if (Kernel::VERSION_ID < 60400) {
            $this->assertResponseMatchesPattern(
                <<<'JSON'
                {
                    "type": "https://symfony.com/errors/validation",
                    "title": "Validation Failed",
                    "detail": "email: This value should not be blank.",
                    "violations": [
                        {
                            "propertyPath": "email",
                            "title": "This value should not be blank.",
                            "parameters": {
                                "{{ value }}": "\"\""
                            },
                            "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
                        }
                    ]
                }
                JSON
            );

            return;
        }

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "type": "https://symfony.com/errors/validation",
                "title": "Validation Failed",
                "detail": "email: This value should not be blank.",
                "violations": [
                    {
                        "propertyPath": "email",
                        "title": "This value should not be blank.",
                        "template": "This value should not be blank.",
                        "parameters": {
                            "{{ value }}": "\"\""
                        },
                        "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
                    }
                ]
            }
        JSON
        );
    }

    #[Test]
    public function it_allows_updating_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $data =
            <<<'JSON'
            {
                "email": "calvin.klein@bttf.com"
            }
            JSON
        ;

        $this->client->request('PUT', '/ajax/subscriptions/' . $subscription->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_does_not_allow_to_update_a_subscription_if_there_is_a_validation_error(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $data =
            <<<EOT
        {
            "email": ""
        }
EOT;

        $this->client->request('PUT', '/ajax/subscriptions/' . $subscription->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        if (Kernel::VERSION_ID < 60400) {
            $this->assertResponseMatchesPattern(
                <<<'JSON'
                {
                    "type": "https://symfony.com/errors/validation",
                    "title": "Validation Failed",
                    "detail": "email: This value should not be blank.",
                    "violations": [
                        {
                            "propertyPath": "email",
                            "title": "This value should not be blank.",
                            "parameters": {
                                "{{ value }}": "\"\""
                            },
                            "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
                        }
                    ]
                }
                JSON
            );

            return;
        }

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "type": "https://symfony.com/errors/validation",
                "title": "Validation Failed",
                "detail": "email: This value should not be blank.",
                "violations": [
                    {
                        "propertyPath": "email",
                        "title": "This value should not be blank.",
                        "template": "This value should not be blank.",
                        "parameters": {
                            "{{ value }}": "\"\""
                        },
                        "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
                    }
                ]
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_removing_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $this->client->request('DELETE', '/ajax/subscriptions/' . $subscription->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
