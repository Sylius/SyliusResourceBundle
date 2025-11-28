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

use App\Subscription\Entity\Subscription;
use App\Subscription\Foundry\Factory\SubscriptionFactory;
use App\Subscription\Foundry\Story\DefaultSubscriptionsStory;
use App\Tests\Trait\UiTestTrait;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class SubscriptionUiTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;
    use UiTestTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    #[Test]
    public function it_allows_showing_a_subscription(): void
    {
        $subscription = SubscriptionFactory::new()
            ->withEmail('marty.mcfly@bttf.com')
            ->create()
        ;

        $this->client->request('GET', '/admin/subscriptions/' . $subscription->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $content = $this->client->getResponse()->getContent();
        $this->assertStringContainsString(sprintf('ID: %s', $subscription->getId()), $content);
        $this->assertStringContainsString('Email: marty.mcfly@bttf.com', $content);
        $this->assertStringContainsString('Foo: bar', $content);
    }

    #[Test]
    public function it_allows_browsing_subscriptions(): void
    {
        DefaultSubscriptionsStory::load();

        $docBrownSubscription = SubscriptionFactory::find(['email' => 'doc.brown@bttf.com']);
        $biffTannenSubscription = SubscriptionFactory::find(['email' => 'biff.tannen@bttf.com']);

        $this->client->request('GET', '/admin/subscriptions');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $content = $this->client->getResponse()->getContent();

        $this->assertSelectorCount(5, 'tbody tr');

        $this->assertStringContainsString('<td>doc.brown@bttf.com</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s">Show</a>', $docBrownSubscription->getId()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s/edit">Edit</a>', $docBrownSubscription->getId()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/subscriptions/%s/delete" method="post">', $docBrownSubscription->getId()), $content);

        $this->assertStringContainsString('<td>biff.tannen@bttf.com</td>', $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s">Show</a>', $biffTannenSubscription->getId()), $content);
        $this->assertStringContainsString(sprintf('<a href="/admin/subscriptions/%s/edit">Edit</a>', $biffTannenSubscription->getId()), $content);
        $this->assertStringContainsString(sprintf('<form action="/admin/subscriptions/%s/delete" method="post">', $biffTannenSubscription->getId()), $content);
    }

    #[Test]
    public function it_allows_browsing_subscriptions_with_page_limit(): void
    {
        DefaultSubscriptionsStory::load();

        $this->client->request('GET', '/admin/subscriptions?limit=3');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorCount(3, 'tbody tr');
    }

    #[Test]
    public function it_allows_browsing_subscriptions_with_grid_limits(): void
    {
        DefaultSubscriptionsStory::load();

        $this->client->request('GET', '/admin/subscriptions');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorCount(5, 'tbody tr');
    }

    #[Test]
    public function it_allows_creating_a_subscription(): void
    {
        $this->client->request('GET', '/admin/subscriptions/new');

        $content = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('value="new@example.com"', $content);

        $this->submitForm('Create', [
            'subscription[email]' => 'biff.tannen@bttf.com',
        ]);

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        /** @var Subscription $subscription */
        $subscription = self::getContainer()->get('app.repository.subscription')->findOneBy(['email' => 'biff.tannen@bttf.com']);

        $this->assertNotNull($subscription);
        $this->assertSame('biff.tannen@bttf.com', (string) $subscription->email);
    }

    #[Test]
    public function it_does_not_allow_to_create_a_subscription_if_there_is_a_validation_error(): void
    {
        $this->client->request('GET', '/admin/subscriptions/new');

        $this->submitForm('Create', [
            'subscription[email]' => null,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function it_allows_updating_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $this->client->request('GET', '/admin/subscriptions/' . $subscription->getId() . '/edit');

        $this->submitForm('Save changes', [
            'subscription[email]' => 'biff.tannen@bttf.com',
        ]);

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        $subscription->_refresh();
        $this->assertSame('biff.tannen@bttf.com', (string) $subscription->email);
    }

    #[Test]
    public function it_does_not_allow_to_update_a_subscription_if_there_is_a_validation_error(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $this->client->request('GET', '/admin/subscriptions/' . $subscription->getId() . '/edit');

        $this->submitForm('Save changes', [
            'subscription[email]' => null,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function it_allows_deleting_a_subscription(): void
    {
        SubscriptionFactory::createOne();

        $this->client->request('GET', '/admin/subscriptions');

        $this->submitForm('Delete');

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        /** @var Subscription[] $subscriptions */
        $subscriptions = self::getContainer()->get('app.repository.subscription')->findAll();

        $this->assertEmpty($subscriptions);
    }

    #[Test]
    public function it_allows_deleting_multiple_subscriptions(): void
    {
        DefaultSubscriptionsStory::load();

        $this->client->request('GET', '/admin/subscriptions?limit=10');

        $this->submitForm('Bulk delete');

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        /** @var Subscription[] $subscriptions */
        $subscriptions = self::getContainer()->get('app.repository.subscription')->findAll();

        $this->assertEmpty($subscriptions);
    }

    #[Test]
    public function it_allows_accepting_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $this->client->request('GET', '/admin/subscriptions');

        $this->submitForm('Accept');

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        $subscription->_refresh();

        $this->assertSame('accepted', $subscription->getState());
    }

    #[Test]
    public function it_allows_accepting_multiple_subscription(): void
    {
        DefaultSubscriptionsStory::load();
        $martyMcFly = SubscriptionFactory::find(['email' => 'marty.mcfly@bttf.com']);
        $docBrown = SubscriptionFactory::find(['email' => 'doc.brown@bttf.com']);

        $this->client->request('GET', '/admin/subscriptions?limit=10');

        $this->submitForm('Bulk accept');

        $this->assertResponseRedirects(null, Response::HTTP_FOUND);

        $martyMcFly->_refresh();
        $this->assertSame('accepted', $martyMcFly->getState());

        $docBrown->_refresh();
        $this->assertSame('accepted', $docBrown->getState());
    }
}
