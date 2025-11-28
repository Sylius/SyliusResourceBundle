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
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Symfony\Component\HttpFoundation\Response;

trait JsonApiTestTrait
{
    abstract protected static function getContainer();

    protected function markAsSkippedIfNecessary(): void
    {
        $container = self::getContainer();

        if (!$container->hasParameter('sylius.resource.settings')) {
            return;
        }

        $stateMachine = $container->getParameter('sylius.resource.settings')['state_machine_component'];

        if (ResourceBundleInterface::STATE_MACHINE_SYMFONY !== $stateMachine) {
            $this->markTestSkipped('Test skipped because Symphony State Machine is not enabled.');
        }
    }

    protected function assertResponse(Response $response, string $filename, int $expectedStatusCode): void
    {
        $this->assertSame(
            $expectedStatusCode,
            $response->getStatusCode(),
            sprintf('Expected HTTP status code %d, but got %d.', $expectedStatusCode, $response->getStatusCode()),
        );

        $filepath = __DIR__ . '/../Responses/' . $filename . '.json';

        if (!file_exists($filepath)) {
            throw new \RuntimeException(sprintf('Response file not found at "%s"', $filepath));
        }

        $expectedJson = file_get_contents($filepath);
        $actualJson = $response->getContent();

        $matcherFactory = new MatcherFactory();
        $matcher = $matcherFactory->createMatcher(new VoidBacktrace());

        if (!$matcher->match($actualJson, $expectedJson)) {
            $this->fail(sprintf(
                "Response JSON does not match the expected pattern from file '%s'.\nError: %s",
                $filename,
                $matcher->getError(),
            ));
        }
    }
}
