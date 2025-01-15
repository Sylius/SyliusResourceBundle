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
use Tests\PurgeDatabaseTrait;

final class GedmoApiTest extends ApiTestCase
{
    use PurgeDatabaseTrait;

    #[Test]
    public function it_allows_creating_a_comic_book(): void
    {
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
}
