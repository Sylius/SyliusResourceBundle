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

final class PullRequestApiTest extends JsonApiTestCase
{
    /** @test */
    public function it_allows_creating_a_pull_request()
    {
        $data =
<<<EOT
        {
        }
EOT;

        $this->client->request('POST', '/pull-requests/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'pull-requests/create_response', Response::HTTP_CREATED);
    }

    /** @test */
    public function it_allows_submitting_a_pull_request()
    {
        $objects = $this->loadFixturesFromFile('pull-requests.yml');

        $data =
            <<<EOT
        {
        }
EOT;

        $this->client->request('PUT', '/pull-requests/' . $objects['pull_request_start']->getId() . '/submit', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'pull-requests/submit_response', Response::HTTP_OK);
    }

    /** @test */
    public function it_allows_waiting_for_review_a_pull_request()
    {
        $objects = $this->loadFixturesFromFile('pull-requests.yml');

        $data =
            <<<EOT
        {
        }
EOT;

        $this->client->request('PUT', '/pull-requests/' . $objects['pull_request_test']->getId() . '/wait_for_review', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'pull-requests/wait_for_review_response', Response::HTTP_OK);
    }

    /** @test */
    public function it_does_not_allow_to_wait_for_review_on_pull_request_with_start_status()
    {
        $objects = $this->loadFixturesFromFile('pull-requests.yml');

        $data =
            <<<EOT
        {
        }
EOT;

        $this->client->request('PUT', '/pull-requests/' . $objects['pull_request_start']->getId() . '/wait_for_review', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_BAD_REQUEST);
    }
}
