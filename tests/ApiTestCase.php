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

namespace Tests;

use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Factory\MatcherFactory;
use Coduo\PHPMatcher\Matcher;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use PHPUnit\Framework\Attributes\Before;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{
    use PHPMatcherAssertions;

    protected KernelBrowser $client;

    protected Matcher $matcher;

    #[Before]
    protected function _createClient(): void
    {
        $this->client = self::createClient();
    }

    #[Before]
    protected static function _createMatcher(): Matcher
    {
        return (new MatcherFactory())->createMatcher(new VoidBacktrace());
    }

    protected function assertResponseMatchesPattern(string $pattern): void
    {
        $response = $this->client->getResponse();
        $content = $response->getContent();

        self::assertMatchesPattern($pattern, $content);
    }
}
