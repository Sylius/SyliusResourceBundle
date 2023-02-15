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

final class SubscriptionApiTest extends ApiTestCase
{
    /** @test */
    public function it_allows_showing_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/' . $subscriptions['subscription_marty']->getId() . '.json');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertEquals(sprintf('{"id":"%s","email":"marty.mcfly@bttf.com"}', $subscriptions['subscription_marty']->getId()), $content);
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
