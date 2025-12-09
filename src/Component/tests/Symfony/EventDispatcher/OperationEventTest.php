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
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;

final class OperationEventTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $event = new OperationEvent();

        $this->assertInstanceOf(OperationEvent::class, $event);
    }

    public function testItCanBeCreatedWithSubject(): void
    {
        $subject = new \stdClass();
        $event = new OperationEvent($subject);

        $this->assertSame($subject, $event->getSubject());
    }

    public function testItCanGetOperation(): void
    {
        $operation = new Create();
        $event = new OperationEvent();
        $event->setArgument('operation', $operation);

        $this->assertSame($operation, $event->getOperation());
    }

    public function testItCanGetContext(): void
    {
        $context = new Context();
        $event = new OperationEvent();
        $event->setArgument('context', $context);

        $this->assertSame($context, $event->getContext());
    }

    public function testItCanGetOperationAndContext(): void
    {
        $operation = new Create();
        $context = new Context();
        $subject = new \stdClass();

        $event = new OperationEvent($subject);
        $event->setArgument('operation', $operation);
        $event->setArgument('context', $context);

        $this->assertSame($subject, $event->getSubject());
        $this->assertSame($operation, $event->getOperation());
        $this->assertSame($context, $event->getContext());
    }
}
