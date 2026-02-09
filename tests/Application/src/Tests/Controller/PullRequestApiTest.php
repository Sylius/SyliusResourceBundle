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
use FOS\RestBundle\FOSRestBundle;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Registry;
use Tests\ApiTestCase;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
final class PullRequestApiTest extends ApiTestCase
{
    protected function setUp(): void
    {
        $this->markAsSkippedIfNoStateMachineIsAvailable();
        $this->markAsSkippedIfFosRestBundleIsNotAvailable();
    }

    #[Test]
    public function it_allows_creating_a_pull_request(): void
    {
        $this->markAsSkippedIfBcLayerIsEnabled();
        $this->client->request('POST', '/pull-requests', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": "start"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_creating_a_pull_request_with_bc_layer(): void
    {
        $this->markAsSkippedIfBcLayerIsNotEnabled();
        $this->client->request('POST', '/pull-requests/', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": "start"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_submitting_a_pull_request(): void
    {
        $pullRequest = PullRequestFactory::new()
            ->withCurrentPlace('start')
            ->create()
        ;

        $this->client->request('PUT', '/pull-requests/' . $pullRequest->getId() . '/submit', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": "test"
            }
            JSON
        );
    }

    #[Test]
    public function it_allows_waiting_for_review_a_pull_request(): void
    {
        $pullRequest = PullRequestFactory::new()
            ->withCurrentPlace('test')
            ->create()
        ;

        $this->client->request('PUT', '/pull-requests/' . $pullRequest->getId() . '/wait_for_review', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": "review"
            }
            JSON
        );
    }

    #[Test]
    public function it_does_not_allow_to_wait_for_review_on_pull_request_with_start_status(): void
    {
        $pullRequest = PullRequestFactory::new()
            ->withCurrentPlace('start')
            ->create()
        ;

        $this->client->request('PUT', '/pull-requests/' . $pullRequest->getId() . '/wait_for_review', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');
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

    private function markAsSkippedIfFosRestBundleIsNotAvailable(): void
    {
        if (!class_exists(FOSRestBundle::class)) {
            $this->markTestSkipped('FriendsOfSymfony Rest Bundle is not installed.');
        }
    }

    private function markAsSkippedIfNoStateMachineIsAvailable(): void
    {
        if (!class_exists(Registry::class) || !class_exists(winzouStateMachineBundle::class)) {
            $this->markTestSkipped('No state machine available.');
        }
    }
}
