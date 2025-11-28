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

use App\Foundry\Factory\PullRequestFactory;
use App\Tests\Trait\JsonApiTestTrait;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class PullRequestApiTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;
    use JsonApiTestTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    #[Test]
    public function it_allows_creating_a_pull_request(): void
    {
        $this->client->request('POST', '/pull-requests/', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'pull-requests/create_response', Response::HTTP_CREATED);
    }

    #[Test]
    public function it_allows_submitting_a_pull_request(): void
    {
        $pullRequest = PullRequestFactory::new()
            ->withCurrentPlace('start')
            ->create()
        ;

        $this->client->request('PUT', '/pull-requests/' . $pullRequest->getId() . '/submit', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'pull-requests/submit_response', Response::HTTP_OK);
    }

    #[Test]
    public function it_allows_waiting_for_review_a_pull_request(): void
    {
        $pullRequest = PullRequestFactory::new()
            ->withCurrentPlace('test')
            ->create()
        ;

        $this->client->request('PUT', '/pull-requests/' . $pullRequest->getId() . '/wait_for_review', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'pull-requests/wait_for_review_response', Response::HTTP_OK);
    }

    #[Test]
    public function it_does_not_allow_to_wait_for_review_on_pull_request_with_start_status(): void
    {
        $pullRequest = PullRequestFactory::new()
            ->withCurrentPlace('start')
            ->create()
        ;

        $this->client->request('PUT', '/pull-requests/' . $pullRequest->getId() . '/wait_for_review', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
