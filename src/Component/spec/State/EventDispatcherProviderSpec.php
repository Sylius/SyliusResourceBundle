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

namespace spec\Sylius\Component\Resource\State;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\State\EventDispatcherProvider;
use Sylius\Component\Resource\State\ProviderInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;
use Sylius\Resource\Context\Context;

final class EventDispatcherProviderSpec extends ObjectBehavior
{
    function let(
        ProviderInterface $decorated,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $this->beConstructedWith($decorated, $operationEventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(EventDispatcherProvider::class);
    }

    function it_dispatches_events_for_index_operation(
        ProviderInterface $decorated,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $operation = new Index(provider: '\App\Provider');
        $context = new Context();

        $operationEvent = new OperationEvent();

        $decorated->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->willReturn($operationEvent)->shouldBeCalled();
        $decorated->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->shouldBeCalled();

        $this->provide($operation, $context);
    }

    function it_dispatches_events_for_show_operation(
        ProviderInterface $decorated,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $operation = new Show(provider: '\App\Provider');
        $context = new Context();

        $operationEvent = new OperationEvent();

        $decorated->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->willReturn($operationEvent)->shouldBeCalled();
        $decorated->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->shouldBeCalled();

        $this->provide($operation, $context);
    }

    function it_does_not_dispatch_events_for_create_operation(
        ProviderInterface $decorated,
        OperationEventDispatcherInterface $operationEventDispatcher,
    ): void {
        $operation = new Create(provider: '\App\Provider');
        $context = new Context();

        $decorated->provide($operation, $context)->shouldBeCalled();

        $operationEventDispatcher->dispatch(null, $operation, $context)->shouldNotBeCalled();

        $this->provide($operation, $context);
    }
}
