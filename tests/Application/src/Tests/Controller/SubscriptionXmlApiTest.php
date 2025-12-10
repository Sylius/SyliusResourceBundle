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

use App\Kernel;
use App\Subscription\Foundry\Factory\SubscriptionFactory;
use App\Subscription\Foundry\Story\DefaultSubscriptionsStory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class SubscriptionXmlApiTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    private static array $headers = [
        'HTTP_CONTENT_TYPE' => 'application/xml',
        'HTTP_ACCEPT' => 'application/xml',
    ];

    #[Test]
    public function it_allows_showing_a_subscription(): void
    {
        $subscription = SubscriptionFactory::new()
            ->withEmail('marty.mcfly@bttf.com')
            ->create()
        ;

        $this->client->request('GET', '/ajax/subscriptions/' . $subscription->getId(), server: self::$headers);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'text/xml; charset=utf-8');

        $this->assertResponseMatchesPattern(
            <<<'XML'
            <response>
                <state>new</state>
                <email>marty.mcfly@bttf.com</email>
            </response>
            XML
        );
    }

    #[Test]
    public function it_allows_indexing_subscriptions(): void
    {
        DefaultSubscriptionsStory::load();

        $this->client->request('GET', '/ajax/subscriptions', server: self::$headers);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'text/xml; charset=utf-8');

        $this->assertResponseMatchesPattern(
            <<<'XML'
            <response>
              <items>
                <state>new</state>
                <email>marty.mcfly@bttf.com</email>
              </items>
              <items>
                <state>new</state>
                <email>doc.brown@bttf.com</email>
              </items>
              <items>
                <state>accepted</state>
                <email>biff.tannen@bttf.com</email>
              </items>
              <items>
                <state>new</state>
                <email>lorraine.baines@bttf.com</email>
              </items>
              <items>
                <state>new</state>
                <email>george.mcfly@bttf.com</email>
              </items>
              <items>
                <state>new</state>
                <email>jennifer.parker@bttf.com</email>
              </items>
              <pagination>
                <current_page>1</current_page>
                <has_previous_page>0</has_previous_page>
                <has_next_page>0</has_next_page>
                <per_page>10</per_page>
                <total_items>6</total_items>
                <total_pages>1</total_pages>
              </pagination>
            </response>
            XML
        );
    }

    #[Test]
    public function it_allows_creating_a_subscription(): void
    {
        $data =
            <<<'XML'
            <root>
                <email>marty.mcfly@bttf.com</email>
            </root>
            XML
        ;

        $this->client->request(method: 'POST', uri: '/ajax/subscriptions', server: self::$headers, content: $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'text/xml; charset=utf-8');

        $this->assertResponseMatchesPattern(
            <<<'XML'
            <response>
                <state>new</state>
                <email>marty.mcfly@bttf.com</email>
            </response>
            XML
        );
    }

    #[Test]
    public function it_does_not_allow_to_create_a_subscription_if_there_is_a_validation_error(): void
    {
        $data =
            <<<'XML'
            <root>
                <email></email>
            </root>
            XML
        ;

        $this->client->request(method: 'POST', uri: '/ajax/subscriptions', server: self::$headers, content: $data);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseHeaderSame('content-type', 'text/xml; charset=utf-8');

        if (Kernel::VERSION_ID < 60400) {
            $this->assertResponseMatchesPattern(
                <<<'XML'
                <response>
                    <type>https://symfony.com/errors/validation</type>
                    <title>Validation Failed</title>
                    <detail>email: This value should not be blank.</detail>
                    <violations>
                        <propertyPath>email</propertyPath>
                        <title>This value should not be blank.</title>
                        <parameters>
                            <item key="{{ value }}">""</item>
                        </parameters>
                        <type>urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3</type>
                    </violations>
                </response>
                XML
            );

            return;
        }

        $this->assertResponseMatchesPattern(
            <<<'XML'
            <response>
                <type>https://symfony.com/errors/validation</type>
                <title>Validation Failed</title>
                <detail>email: This value should not be blank.</detail>
                <violations>
                    <propertyPath>email</propertyPath>
                    <title>This value should not be blank.</title>
                    <template>This value should not be blank.</template>
                    <parameters>
                        <item key="{{ value }}">""</item>
                    </parameters>
                    <type>urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3</type>
                </violations>
            </response>
            XML
        );
    }

    #[Test]
    public function it_allows_updating_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $data =
            <<<'XML'
            <root>
                <email>calvin.klein@bttf.com</email>
            </root>
            XML
        ;

        $this->client->request(method: 'PUT', uri: '/ajax/subscriptions/' . $subscription->getId(), server: self::$headers, content: $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_does_not_allow_to_update_a_subscription_if_there_is_a_validation_error(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $data =
            <<<'XML'
            <root>
                <email></email>
            </root>
            XML
        ;

        $this->client->request(method: 'PUT', uri: '/ajax/subscriptions/' . $subscription->getId(), server: self::$headers, content: $data);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertResponseHeaderSame('content-type', 'text/xml; charset=utf-8');

        if (Kernel::VERSION_ID < 60400) {
            $this->assertResponseMatchesPattern(
                <<<'XML'
                <response>
                    <type>https://symfony.com/errors/validation</type>
                    <title>Validation Failed</title>
                    <detail>email: This value should not be blank.</detail>
                    <violations>
                        <propertyPath>email</propertyPath>
                        <title>This value should not be blank.</title>
                        <parameters>
                            <item key="{{ value }}">""</item>
                        </parameters>
                        <type>urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3</type>
                    </violations>
                </response>
                XML
            );

            return;
        }

        $this->assertResponseMatchesPattern(
            <<<'XML'
            <response>
                <type>https://symfony.com/errors/validation</type>
                <title>Validation Failed</title>
                <detail>email: This value should not be blank.</detail>
                <violations>
                    <propertyPath>email</propertyPath>
                    <title>This value should not be blank.</title>
                    <template>This value should not be blank.</template>
                    <parameters>
                        <item key="{{ value }}">""</item>
                    </parameters>
                    <type>urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3</type>
                </violations>
            </response>
            XML
        );
    }

    #[Test]
    public function it_allows_removing_a_subscription(): void
    {
        $subscription = SubscriptionFactory::createOne();

        $this->client->request(method: 'DELETE', uri: '/ajax/subscriptions/' . $subscription->getId(), server: self::$headers);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
