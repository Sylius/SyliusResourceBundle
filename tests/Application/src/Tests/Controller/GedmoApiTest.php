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

use Gedmo\Sortable\SortableListener;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

final class GedmoApiTest extends ApiTestCase
{
    use ResetDatabase;

    #[Test]
    public function it_allows_creating_a_comic_book(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();
        $this->markAsSkippedIfGedmoDoctrineExtensionsIsNotAvailable();

        $data =
<<<EOT
        {
            "extra": "Some info"
        }
EOT;

        $this->client->request('POST', '/gedmos', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "position": 0,
                "extra": "Some info"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_creating_a_comic_book_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();
        $this->markAsSkippedIfGedmoDoctrineExtensionsIsNotAvailable();

        $data =
            <<<EOT
        {
            "extra": "Some info"
        }
EOT;

        $this->client->request('POST', '/gedmos/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "position": 0,
                "extra": "Some info"
            }
            JSON
        );
    }

    private function isRoutingPathBcLayerEnabled(): bool
    {
        return (bool) $this->getContainer()->getParameter('sylius.routing_path_bc_layer');
    }

    private function markAsSkippedIfBcLayerIsEnabled(): void
    {
        if ($this->isRoutingPathBcLayerEnabled()) {
            $this->markTestSkipped('This test requires The BC layer to be disabled.');
        }
    }

    private function markAsSkippedIfBcLayerIsNotEnabled(): void
    {
        if (!$this->isRoutingPathBcLayerEnabled()) {
            $this->markTestSkipped('This test requires The BC layer to be enabled.');
        }
    }

    private function markAsSkippedIfGedmoDoctrineExtensionsIsNotAvailable(): void
    {
        if (!class_exists(SortableListener::class)) {
            $this->markTestSkipped('Gedmo Doctrine Extension is not available.');
        }
    }
}
