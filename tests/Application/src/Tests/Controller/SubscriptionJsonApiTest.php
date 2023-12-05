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
use App\Kernel;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;

final class SubscriptionJsonApiTest extends JsonApiTestCase
{
    /** @test */
    public function it_allows_showing_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/ajax/subscriptions/' . $subscriptions['subscription_marty']->getId());
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'subscriptions/show_response', Response::HTTP_OK);
    }

    /** @test */
    public function it_allows_indexing_subscriptions(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/ajax/subscriptions');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'subscriptions/index_response', Response::HTTP_OK);
    }

    /** @test */
    public function it_allows_creating_a_subscription(): void
    {
        $data =
            <<<EOT
        {
            "email": "marty.mcfly@bttf.com"
        }
EOT;

        $this->client->request('POST', '/ajax/subscriptions', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'subscriptions/create_response', Response::HTTP_CREATED);
    }

    /** @test */
    public function it_does_not_allow_to_create_a_subscription_if_there_is_a_validation_error(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $data =
            <<<EOT
        {
            "email": ""
        }
EOT;

        $this->client->request('POST', '/ajax/subscriptions', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $file = Kernel::VERSION_ID >= 60400 ? 'subscriptions/create_validation' : 'subscriptions/create_validation_legacy';

        $this->assertResponse($this->client->getResponse(), $file, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_updating_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $data =
            <<<EOT
        {
            "email": "calvin.klein@bttf.com"
        }
EOT;

        $this->client->request('PUT', '/ajax/subscriptions/' . $subscriptions['subscription_marty']->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_does_not_allow_to_update_a_subscription_if_there_is_a_validation_error(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $data =
            <<<EOT
        {
            "email": ""
        }
EOT;

        $this->client->request('PUT', '/ajax/subscriptions/' . $subscriptions['subscription_marty']->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $file = Kernel::VERSION_ID >= 60400 ? 'subscriptions/update_validation' : 'subscriptions/update_validation_legacy';

        $this->assertResponse($this->client->getResponse(), $file, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_removing_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('DELETE', '/ajax/subscriptions/' . $subscriptions['subscription_marty']->getId());
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
