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

use App\Foundry\Factory\BlogPostFactory;
use App\Tests\Trait\JsonApiTestTrait;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class BlogPostApiTest extends WebTestCase
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

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
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

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
