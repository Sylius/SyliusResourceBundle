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
use Sylius\Component\Resource\Metadata\Api\Delete;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\State\BulkAwareProcessor;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

final class BulkAwareProcessorSpec extends ObjectBehavior
{
    function let(ProcessorInterface $decorated, OperationEventDispatcherInterface $operationEventDispatcher): void
    {
        $this->beConstructedWith($decorated, $operationEventDispatcher);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(BulkAwareProcessor::class);
    }

    function it_calls_decorated_processor_for_each_data_for_bulk_operation(
        ProcessorInterface $decorated,
        \stdClass $firstItem,
        \stdClass $secondItem,
    ): void {
        $operation = new BulkDelete();
        $context = new Context();

        $data = [
            $firstItem->getWrappedObject(),
            $secondItem->getWrappedObject(),
        ];

        $decorated->process($firstItem, $operation, $context)->shouldBeCalled();
        $decorated->process($secondItem, $operation, $context)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn(null);
    }

    function it_calls_decorated_processor_for_data_for_other_operation_than_bulk_one(
        ProcessorInterface $decorated,
        \stdClass $data,
        \stdClass $result,
    ): void {
        $operation = new Delete();
        $context = new Context();

        $decorated->process($data, $operation, $context)->willReturn($result)->shouldBeCalled();

        $this->process($data, $operation, $context)->shouldReturn($result);
    }
}
