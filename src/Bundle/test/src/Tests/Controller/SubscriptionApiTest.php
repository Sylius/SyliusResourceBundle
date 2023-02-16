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
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;

final class SubscriptionApiTest extends JsonApiTestCase
{
    /** @test */
    public function it_allows_showing_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/ajax/subscriptions/' . $subscriptions['subscription_marty']->getId());
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertEquals(sprintf('{"id":"%s","email":"marty.mcfly@bttf.com"}', $subscriptions['subscription_marty']->getId()), $content);
    }

    /** @test */
    public function it_allows_indexing_subscriptions(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/ajax/subscriptions');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertEquals(sprintf(
            '{"items":[{"id":"%s","email":"marty.mcfly@bttf.com"},{"id":"%s","email":"doc.brown@bttf.com"}],"pagination":{"current_page":1,"has_previous_page":false,"has_next_page":false,"per_page":10,"total_items":2,"total_pages":1}}',
            $subscriptions['subscription_marty']->getId(),
            $subscriptions['subscription_doc']->getId(),
        ), $content);
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
