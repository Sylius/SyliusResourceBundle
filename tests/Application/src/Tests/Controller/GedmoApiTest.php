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
        $this->markAsSkippedIfGedmoDoctrineExtensionsIsNotAvailable();

        $data =
<<<EOT
        {
            "extra": "Some info"
        }
EOT;

        $this->client->request('POST', $this->isRoutingPathBcLayerEnabled() ? '/gedmos/' : '/gedmos', [], [], ['CONTENT_TYPE' => 'application/json'], $data);

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

    private function markAsSkippedIfGedmoDoctrineExtensionsIsNotAvailable(): void
    {
        if (!class_exists(SortableListener::class)) {
            $this->markTestSkipped('Gedmo Doctrine Extension is not available.');
        }
    }
}
