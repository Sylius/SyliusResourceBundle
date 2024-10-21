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

namespace Sylius\Resource\Tests\Symfony\EventDispatcher\State;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\State\DispatchPostReadEventProvider;

final class DispatchPostReadEventProviderTest extends TestCase
{
    private DispatchPostReadEventProvider $dispatchPostReadEventProvider;

    private ProviderInterface $provider;

    private OperationEventDispatcherInterface $operationEventDispatcher;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(ProviderInterface::class);
        $this->operationEventDispatcher = $this->createMock(OperationEventDispatcherInterface::class);
        $this->dispatchPostReadEventProvider = new DispatchPostReadEventProvider(
            $this->provider,
            $this->operationEventDispatcher,
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(DispatchPostReadEventProvider::class, $this->dispatchPostReadEventProvider);
    }

    public function testItDispatchesEventsForIndexOperation(): void
    {
        $operation = new Index(provider: '\App\Provider');
        $context = new Context();
        $operationEvent = new OperationEvent();

        $this->provider->expects($this->once())
            ->method('provide')
            ->with($operation, $context);

        $this->operationEventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(null, $operation, $context)
            ->willReturn($operationEvent);

        $this->dispatchPostReadEventProvider->provide($operation, $context);
    }

    public function testItDispatchesEventsForShowOperation(): void
    {
        $operation = new Show(provider: '\App\Provider');
        $context = new Context();
        $operationEvent = new OperationEvent();

        $this->provider->expects($this->once())
            ->method('provide')
            ->with($operation, $context);

        $this->operationEventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(null, $operation, $context)
            ->willReturn($operationEvent);

        $this->dispatchPostReadEventProvider->provide($operation, $context);
    }

    public function testItDoesNotDispatchEventsForCreateOperation(): void
    {
        $operation = new Create(provider: '\App\Provider');
        $context = new Context();

        $this->provider->expects($this->once())
            ->method('provide')
            ->with($operation, $context);

        $this->operationEventDispatcher->expects($this->never())
            ->method('dispatch');

        $this->dispatchPostReadEventProvider->provide($operation, $context);
    }
}
