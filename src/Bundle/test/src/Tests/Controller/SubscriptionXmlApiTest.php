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

use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;

final class SubscriptionXmlApiTest extends XmlApiTestCase
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
        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email>marty.mcfly@bttf.com</email>
</root>
EOT;

        $this->client->request(method: 'POST', uri: '/ajax/subscriptions', server: self::$headersWithContentType, content: $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'subscriptions/create_response', Response::HTTP_CREATED);
    }

    /** @test */
    public function it_does_not_allow_to_create_a_subscription_if_there_is_a_validation_error(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email></email>
</root>
EOT;

        $this->client->request(method: 'POST', uri: '/ajax/subscriptions', server: self::$headersWithContentType, content: $data);

        $this->assertResponse($this->client->getResponse(), 'subscriptions/create_validation', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_updating_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email>calvin.klein@bttf.com</email>
</root>
EOT;

        $this->client->request(method: 'PUT', uri: '/ajax/subscriptions/' . $subscriptions['subscription_marty']->getId(), server: self::$headersWithContentType, content: $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_does_not_allow_to_update_a_subscription_if_there_is_a_validation_error(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $data = <<<EOT
<?xml version="1.0"?>
<root>
	<email></email>
</root>
EOT;

        $this->client->request(method: 'PUT', uri: '/ajax/subscriptions/' . $subscriptions['subscription_marty']->getId(), server: self::$headersWithContentType, content: $data);

        $this->assertResponse($this->client->getResponse(), 'subscriptions/update_validation', Response::HTTP_UNPROCESSABLE_ENTITY);
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
