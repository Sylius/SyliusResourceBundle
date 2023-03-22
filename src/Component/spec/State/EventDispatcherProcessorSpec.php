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

namespace spec\Sylius\Component\Resource\State;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\State\EventDispatcherProcessor;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

final class EventDispatcherProcessorSpec extends ObjectBehavior
{
    function let(ProcessorInterface $decorated, OperationEventDispatcherInterface $operationEventDispatcher): void
    {
        $this->beConstructedWith($decorated, $operationEventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(EventDispatcherProcessor::class);
    }

    function it_dispatches_pre_and_post_events_with_operation_as_string(
        ProcessorInterface $decorated,
        OperationEventDispatcherInterface $operationEventDispatcher,
        \stdClass $data,
        \stdClass $result,
    ): void {
        $operation = new Create(processor: '\App\Processor');
        $context = new Context();

        $decorated->process($data, $operation, $context)->willReturn($result)->shouldBeCalled();

        $operationEventDispatcher->dispatchPreEvent($data, $operation, $context)->shouldBeCalled();
        $operationEventDispatcher->dispatchPostEvent($data, $operation, $context)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn($result);
    }
}
