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
use PHPUnit\Framework\Attributes\Test;
use Sylius\Bundle\ResourceBundle\ResourceBundleInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class BlogPostApiTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    #[Test]
    public function it_allows_creating_a_blog_post(): void
    {
        $this->markAsSkippedIfNecessary();

        $this->client->request('POST', '/blog-posts/', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": {
                    "draft": 1
                }
            }
            JSON
        );
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": {
                    "reviewed": 1
                }
            }
            JSON
        );
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": {
                    "published": 1
                }
            }
            JSON
        );
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

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $this->assertResponseMatchesPattern(
            <<<'JSON'
            {
                "id": @integer@,
                "current_place": {
                    "rejected": 1
                }
            }
            JSON
        );
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

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');
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

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseHeaderSame('content-type', 'application/json');
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
