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

namespace AppBundle\Tests\Controller;

use Lakion\ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GedmoApiTest extends JsonApiTestCase
{
    /**
     * @test
     */
    public function it_allows_creating_a_comic_book()
    {
        $data =
<<<EOT
        {
            "extra": "Some info"
        }
EOT;

        $this->client->request('POST', '/gedmos/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'gedmos/create_response', Response::HTTP_CREATED);
    }
}
