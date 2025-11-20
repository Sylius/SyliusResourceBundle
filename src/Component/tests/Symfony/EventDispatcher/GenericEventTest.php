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

namespace Sylius\Resource\Tests\Symfony\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Response;

final class GenericEventTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $event = new GenericEvent();

        $this->assertInstanceOf(GenericEvent::class, $event);
    }

    public function testItCanSetAndGetMessageType(): void
    {
        $event = new GenericEvent();
        $event->setMessageType(GenericEvent::TYPE_SUCCESS);

        $this->assertSame(GenericEvent::TYPE_SUCCESS, $event->getMessageType());
    }

    public function testItCanSetAndGetMessage(): void
    {
        $event = new GenericEvent();
        $event->setMessage('Test message');

        $this->assertSame('Test message', $event->getMessage());
    }

    public function testItCanSetAndGetMessageParameters(): void
    {
        $event = new GenericEvent();
        $parameters = ['%name%' => 'John', '%count%' => 5];
        $event->setMessageParameters($parameters);

        $this->assertSame($parameters, $event->getMessageParameters());
    }

    public function testItCanSetAndGetErrorCode(): void
    {
        $event = new GenericEvent();
        $event->setErrorCode(404);

        $this->assertSame(404, $event->getErrorCode());
    }

    public function testItCanSetAndCheckResponse(): void
    {
        $event = new GenericEvent();
        $response = new Response();

        $this->assertFalse($event->hasResponse());

        $event->setResponse($response);

        $this->assertTrue($event->hasResponse());
        $this->assertSame($response, $event->getResponse());
    }

    public function testItReturnsNullWhenNoResponse(): void
    {
        $event = new GenericEvent();

        $this->assertNull($event->getResponse());
    }

    public function testItCanStopPropagationWithDefaultValues(): void
    {
        $event = new GenericEvent();
        $event->stop('Error occurred');

        $this->assertTrue($event->isStopped());
        $this->assertSame('Error occurred', $event->getMessage());
        $this->assertSame(GenericEvent::TYPE_ERROR, $event->getMessageType());
        $this->assertSame([], $event->getMessageParameters());
        $this->assertSame(500, $event->getErrorCode());
    }

    public function testItCanStopPropagationWithCustomType(): void
    {
        $event = new GenericEvent();
        $event->stop('Warning message', GenericEvent::TYPE_WARNING);

        $this->assertTrue($event->isStopped());
        $this->assertSame('Warning message', $event->getMessage());
        $this->assertSame(GenericEvent::TYPE_WARNING, $event->getMessageType());
        $this->assertSame(500, $event->getErrorCode());
    }

    public function testItCanStopPropagationWithCustomParameters(): void
    {
        $event = new GenericEvent();
        $parameters = ['%item%' => 'Product'];
        $event->stop('Item not found', GenericEvent::TYPE_ERROR, $parameters);

        $this->assertTrue($event->isStopped());
        $this->assertSame('Item not found', $event->getMessage());
        $this->assertSame(GenericEvent::TYPE_ERROR, $event->getMessageType());
        $this->assertSame($parameters, $event->getMessageParameters());
        $this->assertSame(500, $event->getErrorCode());
    }

    public function testItCanStopPropagationWithCustomErrorCode(): void
    {
        $event = new GenericEvent();
        $event->stop('Not found', GenericEvent::TYPE_ERROR, [], 404);

        $this->assertTrue($event->isStopped());
        $this->assertSame('Not found', $event->getMessage());
        $this->assertSame(GenericEvent::TYPE_ERROR, $event->getMessageType());
        $this->assertSame([], $event->getMessageParameters());
        $this->assertSame(404, $event->getErrorCode());
    }

    public function testItCanStopPropagationWithAllParameters(): void
    {
        $event = new GenericEvent();
        $parameters = ['%name%' => 'User', '%id%' => 123];
        $event->stop('User not found', GenericEvent::TYPE_WARNING, $parameters, 404);

        $this->assertTrue($event->isStopped());
        $this->assertSame('User not found', $event->getMessage());
        $this->assertSame(GenericEvent::TYPE_WARNING, $event->getMessageType());
        $this->assertSame($parameters, $event->getMessageParameters());
        $this->assertSame(404, $event->getErrorCode());
    }

    public function testItHasTypeErrorConstant(): void
    {
        $this->assertSame('error', GenericEvent::TYPE_ERROR);
    }

    public function testItHasTypeWarningConstant(): void
    {
        $this->assertSame('warning', GenericEvent::TYPE_WARNING);
    }

    public function testItHasTypeInfoConstant(): void
    {
        $this->assertSame('info', GenericEvent::TYPE_INFO);
    }

    public function testItHasTypeSuccessConstant(): void
    {
        $this->assertSame('success', GenericEvent::TYPE_SUCCESS);
    }

    public function testItCanBeCreatedWithSubject(): void
    {
        $subject = new \stdClass();
        $event = new GenericEvent($subject);

        $this->assertSame($subject, $event->getSubject());
    }

    public function testItCanBeCreatedWithSubjectAndArguments(): void
    {
        $subject = new \stdClass();
        $arguments = ['key1' => 'value1', 'key2' => 'value2'];
        $event = new GenericEvent($subject, $arguments);

        $this->assertSame($subject, $event->getSubject());
        $this->assertSame('value1', $event->getArgument('key1'));
        $this->assertSame('value2', $event->getArgument('key2'));
    }

    public function testItReturnsEmptyStringForMessageTypeByDefault(): void
    {
        $event = new GenericEvent();

        $this->assertSame('', $event->getMessageType());
    }

    public function testItReturnsEmptyStringForMessageByDefault(): void
    {
        $event = new GenericEvent();

        $this->assertSame('', $event->getMessage());
    }

    public function testItReturnsEmptyArrayForMessageParametersByDefault(): void
    {
        $event = new GenericEvent();

        $this->assertSame([], $event->getMessageParameters());
    }

    public function testItReturns500ForErrorCodeByDefault(): void
    {
        $event = new GenericEvent();

        $this->assertSame(500, $event->getErrorCode());
    }

    public function testItIsNotStoppedByDefault(): void
    {
        $event = new GenericEvent();

        $this->assertFalse($event->isStopped());
    }
}
