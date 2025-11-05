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

namespace Sylius\Bundle\ResourceBundle\Tests\Bundle\Event;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\HttpFoundation\Response;

final class ResourceControllerEventTest extends TestCase
{
    private ResourceControllerEvent $event;

    protected function setUp(): void
    {
        $this->event = new ResourceControllerEvent('message');
    }

    public function testItHasEmptyMessageByDefault(): void
    {
        $event = new ResourceControllerEvent();

        $this->assertSame('', $event->getMessage());
    }

    public function testItCanBeInstantiatedWithMessage(): void
    {
        $this->assertSame('message', $this->event->getMessage());
    }

    public function testItCanSetAndGetMessage(): void
    {
        $this->event->setMessage('custom_message');

        $this->assertSame('custom_message', $this->event->getMessage());
    }

    public function testItHasEmptyMessageTypeByDefault(): void
    {
        $this->assertSame('', $this->event->getMessageType());
    }

    public function testItCanSetAndGetMessageType(): void
    {
        $this->event->setMessageType(ResourceControllerEvent::TYPE_SUCCESS);

        $this->assertSame(ResourceControllerEvent::TYPE_SUCCESS, $this->event->getMessageType());
    }

    public function testItHasEmptyMessageParametersByDefault(): void
    {
        $this->assertSame([], $this->event->getMessageParameters());
    }

    public function testItCanSetAndGetMessageParameters(): void
    {
        $this->event->setMessageParameters(['parameter_1', 'parameter_2']);

        $this->assertSame(['parameter_1', 'parameter_2'], $this->event->getMessageParameters());
    }

    public function testItIsNotStoppedByDefault(): void
    {
        $this->assertFalse($this->event->isStopped());
    }

    public function testItCanBeStopped(): void
    {
        $this->event->stop('error_message');

        $this->assertTrue($this->event->isStopped());
    }

    public function testItStopsPropagationWhenStopped(): void
    {
        $this->event->stop('error_message', ResourceControllerEvent::TYPE_SUCCESS, ['parameter']);

        $this->assertTrue($this->event->isPropagationStopped());
        $this->assertSame('error_message', $this->event->getMessage());
        $this->assertSame(ResourceControllerEvent::TYPE_SUCCESS, $this->event->getMessageType());
        $this->assertSame(['parameter'], $this->event->getMessageParameters());
    }

    public function testItDoesNotHaveResponseByDefault(): void
    {
        $this->assertFalse($this->event->hasResponse());
    }

    public function testItCanSetAndGetResponse(): void
    {
        $response = new Response();

        $this->event->setResponse($response);

        $this->assertSame($response, $this->event->getResponse());
        $this->assertTrue($this->event->hasResponse());
    }
}
