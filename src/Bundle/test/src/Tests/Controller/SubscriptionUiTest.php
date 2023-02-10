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

use ApiTestCase\ApiTestCase;
use App\Subscription\Entity\Subscription;
use Coduo\PHPMatcher\Backtrace\VoidBacktrace;
use Coduo\PHPMatcher\Matcher;
use Symfony\Component\HttpFoundation\Response;

final class SubscriptionUiTest extends ApiTestCase
{
    /** @test */
    public function it_allows_showing_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/' . $subscriptions['subscription_marty']->getId());
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();
        $this->assertStringContainsString(sprintf('ID: %s', $subscriptions['subscription_marty']->getId()), $content);
        $this->assertStringContainsString('Email: marty.mcfly@bttf.com', $content);
    }

    /** @test */
    public function it_allows_browsing_subscriptions(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions');
        $response = $this->client->getResponse();

        $this->assertResponseCode($response, Response::HTTP_OK);
        $content = $response->getContent();

        $this->assertStringContainsString('<td>marty.mcfly@bttf.com</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s">Show</a>', $subscriptions['subscription_marty']->getId()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s/edit">Edit</a>', $subscriptions['subscription_marty']->getId()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/subscriptions/%s" method="post">', $subscriptions['subscription_marty']->getId()), $content);

        $this->assertStringContainsString('<td>doc.brown@bttf.com</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s">Show</a>', $subscriptions['subscription_doc']->getId()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s/edit">Edit</a>', $subscriptions['subscription_doc']->getId()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/subscriptions/%s" method="post">', $subscriptions['subscription_doc']->getId()), $content);
    }

    /** @test */
    public function it_allows_creating_a_subscription(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/new');
        $this->client->submitForm('Create', [
            'sylius_resource[email]' => 'biff.tannen@bttf.com',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Subscription $subscription */
        $subscription = static::getContainer()->get('app.repository.subscription')->findOneBy(['email' => 'biff.tannen@bttf.com']);

        $this->assertNotNull($subscription);
        $this->assertSame('biff.tannen@bttf.com', (string) $subscription->email);
    }

    /** @test */
    public function it_does_not_allow_to_create_a_subscription_if_there_is_a_validation_error(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/new');
        $this->client->submitForm('Create', [
            'sylius_resource[email]' => null,
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_updating_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/' . $subscriptions['subscription_marty']->getId() . '/edit');
        $this->client->submitForm('Save changes', [
            'sylius_resource[email]' => 'biff.tannen@bttf.com',
        ]);

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Subscription $subscription */
        $subscription = static::getContainer()->get('app.repository.subscription')->findOneBy(['email' => 'biff.tannen@bttf.com']);

        $this->assertNotNull($subscription);
        $this->assertSame('biff.tannen@bttf.com', (string) $subscription->email);
    }

    /** @test */
    public function it_does_not_allow_to_update_a_subscription_if_there_is_a_validation_error(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/' . $subscriptions['subscription_marty']->getId() . '/edit');
        $this->client->submitForm('Save changes', [
            'sylius_resource[email]' => null,
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_deleting_a_subscription(): void
    {
        $this->loadFixturesFromFile('single_subscription.yml');

        $this->client->request('GET', '/admin/subscriptions');
        $this->client->submitForm('Delete');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Subscription[] $subscriptions */
        $subscriptions = static::getContainer()->get('app.repository.subscription')->findAll();

        $this->assertEmpty($subscriptions);
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
