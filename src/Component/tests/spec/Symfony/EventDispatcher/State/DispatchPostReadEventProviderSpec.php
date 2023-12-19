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

namespace spec\Sylius\Resource\Symfony\EventDispatcher\State;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Symfony\EventDispatcher\State\DispatchPostReadEventProvider;

final class DispatchPostReadEventProviderSpec extends ObjectBehavior
{
    function let(
        ProviderInterface $provider,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $this->beConstructedWith($provider, $operationEventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(DispatchPostReadEventProvider::class);
    }

    function it_dispatches_events_for_index_operation(
        ProviderInterface $provider,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $operation = new Index(provider: '\App\Provider');
        $context = new Context();

        $operationEvent = new OperationEvent();

        $provider->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->willReturn($operationEvent)->shouldBeCalled();
        $provider->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->shouldBeCalled();

        $this->provide($operation, $context);
    }

    function it_dispatches_events_for_show_operation(
        ProviderInterface $provider,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $operation = new Show(provider: '\App\Provider');
        $context = new Context();

        $operationEvent = new OperationEvent();

        $provider->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->willReturn($operationEvent)->shouldBeCalled();
        $provider->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->shouldBeCalled();

        $this->provide($operation, $context);
    }

    function it_does_not_dispatch_events_for_create_operation(
        ProviderInterface $provider,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $operation = new Create(provider: '\App\Provider');
        $context = new Context();

        $provider->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->shouldNotBeCalled();

        $this->provide($operation, $context);
    }
}
