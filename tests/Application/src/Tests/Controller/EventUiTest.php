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

use ApiTestCase\ApiTestCase;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;

final class EventUiTest extends ApiTestCase
{
    /** @test */
    public function it_allows_browsing_events(): void
    {
        $boardGames = $this->loadFixturesFromFile('events.yml');

        $this->client->request('GET', '/admin/events');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();

        $this->assertStringContainsString('<li>Name: Sylius Con 2023</li>', $content);
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
