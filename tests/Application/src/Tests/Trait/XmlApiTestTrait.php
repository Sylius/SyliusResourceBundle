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

namespace App\Tests\Trait;

use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Factory\MatcherFactory;
use Symfony\Component\HttpFoundation\Response;

trait XmlApiTestTrait
{
    protected function getXmlHeaders(): array
    {
        return [
            'CONTENT_TYPE' => 'application/xml',
            'HTTP_ACCEPT' => 'application/xml',
        ];
    }

    protected function assertResponse(Response $response, string $filename, int $expectedStatusCode): void
    {
        $this->assertSame(
            $expectedStatusCode,
            $response->getStatusCode(),
            sprintf('Expected HTTP status code %d, but got %d.', $expectedStatusCode, $response->getStatusCode()),
        );

        $filepath = __DIR__ . '/../Responses/' . $filename . '.xml';

        if (!file_exists($filepath)) {
            throw new \RuntimeException(sprintf('Response file not found at "%s"', $filepath));
        }

        $expectedXml = file_get_contents($filepath);
        $actualXml = $response->getContent();

        $normalizedActual = $this->normalizeXml($actualXml);
        $normalizedExpected = $this->normalizeXml($expectedXml);

        $matcherFactory = new MatcherFactory();
        $matcher = $matcherFactory->createMatcher(new VoidBacktrace());

        if (!$matcher->match($normalizedActual, $normalizedExpected)) {
            $this->fail(sprintf(
                "Response XML does not match the expected pattern from file '%s'.\nError: %s",
                $filename,
                $matcher->getError(),
            ));
        }
    }

    private function normalizeXml(string $xml): string
    {
        $xml = preg_replace('/<\?xml.*?\?>/', '', $xml);

        return preg_replace('/>\s+</', '><', trim($xml));
    }
}
