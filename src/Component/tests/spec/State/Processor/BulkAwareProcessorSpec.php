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

namespace spec\Sylius\Resource\State\Processor;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Api\Delete;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\State\Processor\BulkAwareProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

final class BulkAwareProcessorSpec extends ObjectBehavior
{
    function let(ProcessorInterface $processor, OperationEventDispatcherInterface $operationEventDispatcher): void
    {
        $this->beConstructedWith($processor, $operationEventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(BulkAwareProcessor::class);
    }

    function it_calls_decorated_processor_for_each_data_for_bulk_operation(
        ProcessorInterface $processor,
        \stdClass $firstItem,
        \stdClass $secondItem,
    ): void {
        $operation = new BulkDelete();
        $context = new Context();

        $data = [
            $firstItem->getWrappedObject(),
            $secondItem->getWrappedObject(),
        ];

        $processor->process($firstItem, $operation, $context)->willReturn(null)->shouldBeCalled();
        $processor->process($secondItem, $operation, $context)->willReturn(null)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn(null);
    }

    function it_calls_decorated_processor_for_data_for_other_operation_than_bulk_one(
        ProcessorInterface $processor,
        \stdClass $data,
        \stdClass $result,
    ): void {
        $operation = new Delete();
        $context = new Context();

        $processor->process($data, $operation, $context)->willReturn($result)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn($result);
    }
}
