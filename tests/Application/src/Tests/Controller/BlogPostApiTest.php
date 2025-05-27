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

use ApiTestCase\JsonApiTestCase;
use App\Foundry\Factory\BlogPostFactory;
use PHPUnit\Framework\Attributes\Test;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;

final class BlogPostApiTest extends JsonApiTestCase
{
    use Factories;

    #[Test]
    public function it_allows_creating_a_blog_post(): void
    {
        $this->markAsSkippedIfNecessary();

        $this->client->request('POST', '/blog-posts/', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'blog-posts/create_response', Response::HTTP_CREATED);
    }

    #[Test]
    public function it_allows_reviewing_a_blog_post(): void
    {
        $this->markAsSkippedIfNecessary();

        $blogPost = BlogPostFactory::new()
            ->onDraft()
            ->create()
        ;

        $this->client->request('PUT', '/blog-posts/' . $blogPost->getId() . '/to_review', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'blog-posts/to_review_response', Response::HTTP_OK);
    }

    #[Test]
    public function it_allows_publishing_a_blog_post(): void
    {
        $this->markAsSkippedIfNecessary();

        $blogPost = BlogPostFactory::new()
            ->reviewed()
            ->create()
        ;

        $this->client->request('PUT', '/blog-posts/' . $blogPost->getId() . '/publish', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'blog-posts/publish_response', Response::HTTP_OK);
    }

    #[Test]
    public function it_allows_rejecting_a_blog_post(): void
    {
        $this->markAsSkippedIfNecessary();

        $blogPost = BlogPostFactory::new()
            ->reviewed()
            ->create()
        ;

        $this->client->request('PUT', '/blog-posts/' . $blogPost->getId() . '/reject', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'blog-posts/reject_response', Response::HTTP_OK);
    }

    #[Test]
    public function it_does_not_allow_to_publish_a_blog_post_with_draft_status(): void
    {
        $this->markAsSkippedIfNecessary();

        $blogPost = BlogPostFactory::new()
            ->onDraft()
            ->create()
        ;

        $this->client->request('PUT', '/blog-posts/' . $blogPost->getId() . '/publish', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_BAD_REQUEST);
    }

    #[Test]
    public function it_does_not_allow_to_reject_a_blog_post_with_draft_status(): void
    {
        $this->markAsSkippedIfNecessary();

        $blogPost = BlogPostFactory::new()
            ->onDraft()
            ->create()
        ;

        $this->client->request('PUT', '/blog-posts/' . $blogPost->getId() . '/reject', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        $response = $this->client->getResponse();
        $this->assertResponseCode($response, Response::HTTP_BAD_REQUEST);
    }

    private function markAsSkippedIfNecessary(): void
    {
        $container = self::getContainer();

        $stateMachine = $container->getParameter('sylius.resource.settings')['state_machine_component'];

        if (ResourceBundleInterface::STATE_MACHINE_SYMFONY !== $stateMachine) {
            $this->markTestSkipped();
        }
    }
}
