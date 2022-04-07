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

use ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserApiTest extends JsonApiTestCase
{
    /**
     * @test
     */
    public function it_allows_registering_a_user(): void
    {
        $data =
<<<EOT
        {
            "username": "marty1985",
            "password": "ThisIsHeavy"
        }
EOT;

        $this->client->request('POST', '/users/register', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'users/register_response', Response::HTTP_CREATED);
    }

    /**
     * @test
     */
    public function it_allows_changing_password(): void
    {
        $users = $this->loadFixturesFromFile('users.yml');

        $data =
            <<<EOT
        {
            "password": "ThisIsAwesome"
        }
EOT;

        $this->client->request('PATCH', '/users/' . $users['marty']->getId() . '/change_password', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);

        $this->getEntityManager()->refresh($users['marty']);

        $this->assertEquals('ThisIsAwesome', $users['marty']->getPassword());
    }

    /**
     * @test
     */
    public function it_allows_updating_profile(): void
    {
        $users = $this->loadFixturesFromFile('users.yml');

        $data =
            <<<EOT
        {
            "username": "marty1955"
        }
EOT;

        $this->client->request('PATCH', '/users/' . $users['marty']->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);

        $this->getEntityManager()->refresh($users['marty']);

        $this->assertEquals('marty1955', $users['marty']->getUsername());
    }
}
