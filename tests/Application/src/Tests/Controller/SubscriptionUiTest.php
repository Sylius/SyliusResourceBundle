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
        $this->assertStringContainsString('Foo: bar', $content);
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
        $this->assertStringContainsString(sprintf('<form action="/admin/subscriptions/%s/delete" method="post">', $subscriptions['subscription_marty']->getId()), $content);

        $this->assertStringContainsString('<td>doc.brown@bttf.com</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s">Show</a>', $subscriptions['subscription_doc']->getId()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s/edit">Edit</a>', $subscriptions['subscription_doc']->getId()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/subscriptions/%s/delete" method="post">', $subscriptions['subscription_doc']->getId()), $content);

        $this->assertStringContainsString('<td>biff.tannen@bttf.com</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s">Show</a>', $subscriptions['subscription_biff']->getId()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s/edit">Edit</a>', $subscriptions['subscription_biff']->getId()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/subscriptions/%s/delete" method="post">', $subscriptions['subscription_biff']->getId()), $content);
    }

    /** @test */
    public function it_allows_creating_a_subscription(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/new');
        $response = $this->client->getResponse();

        $content = $response->getContent();

        $this->assertStringContainsString('value="new@example.com"', $content);

        $this->client->submitForm('Create', [
            'subscription[email]' => 'biff.tannen@bttf.com',
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
            'subscription[email]' => null,
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_allows_updating_a_subscription(): void
    {
        $subscriptions = $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions/' . $subscriptions['subscription_marty']->getId() . '/edit');
        $this->client->submitForm('Save changes', [
            'subscription[email]' => 'biff.tannen@bttf.com',
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
            'subscription[email]' => null,
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

    /** @test */
    public function it_allows_deleting_multiple_subscriptions(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions');
        $this->client->submitForm('Bulk delete');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Subscription[] $subscriptions */
        $subscriptions = static::getContainer()->get('app.repository.subscription')->findAll();

        $this->assertEmpty($subscriptions);
    }

    /** @test */
    public function it_allows_accepting_a_subscription(): void
    {
        $this->loadFixturesFromFile('single_subscription.yml');

        $this->client->request('GET', '/admin/subscriptions');
        $this->client->submitForm('Accept');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Subscription $subscription */
        $subscription = static::getContainer()->get('app.repository.subscription')->findOneBy(['email' => 'marty.mcfly@bttf.com']);

        $this->assertNotNull($subscription);
        $this->assertSame('accepted', $subscription->getState());
    }

    /** @test */
    public function it_allows_accepting_multiple_subscription(): void
    {
        $this->loadFixturesFromFile('subscriptions.yml');

        $this->client->request('GET', '/admin/subscriptions');
        $this->client->submitForm('Bulk accept');

        $this->assertResponseRedirects(null, expectedCode: Response::HTTP_FOUND);

        /** @var Subscription $firstSubscription */
        $firstSubscription = static::getContainer()->get('app.repository.subscription')->findOneBy(['email' => 'marty.mcfly@bttf.com']);

        /** @var Subscription $secondSubscription */
        $secondSubscription = static::getContainer()->get('app.repository.subscription')->findOneBy(['email' => 'doc.brown@bttf.com']);

        $this->assertNotNull($firstSubscription);
        $this->assertSame('accepted', $firstSubscription->getState());

        $this->assertNotNull($secondSubscription);
        $this->assertSame('accepted', $secondSubscription->getState());
    }

    protected function buildMatcher(): Matcher
    {
        return $this->matcherFactory->createMatcher(new VoidBacktrace());
    }
}
