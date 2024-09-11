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

namespace Sylius\Resource\Tests\State\Processor;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Api\Delete;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\State\Processor\BulkAwareProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventDispatcherInterface;

final class BulkAwareProcessorTest extends TestCase
{
    use ProphecyTrait;

    private BulkAwareProcessor $bulkAwareProcessor;

    private $processor;

    private $operationEventDispatcher;

    protected function setUp(): void
    {
        $this->processor = $this->prophesize(ProcessorInterface::class);
        $this->operationEventDispatcher = $this->prophesize(OperationEventDispatcherInterface::class);
        $this->bulkAwareProcessor = new BulkAwareProcessor($this->processor->reveal(), $this->operationEventDispatcher->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(BulkAwareProcessor::class, $this->bulkAwareProcessor);
    }

    public function testItCallsDecoratedProcessorForEachDataForBulkOperation(): void
    {
        $firstItem = new \stdClass();
        $secondItem = new \stdClass();
        $operation = new BulkDelete();
        $context = new Context();

        $this->processor->process($firstItem, $operation, $context)->willReturn(null)->shouldBeCalled();
        $this->processor->process($secondItem, $operation, $context)->willReturn(null)->shouldBeCalled();

        $data = [$firstItem, $secondItem];

        $this->bulkAwareProcessor->process($data, $operation, $context);
    }

    public function testItCallsDecoratedProcessorForDataForOtherOperationThanBulkOne(): void
    {
        $data = new \stdClass();
        $result = new \stdClass();
        $operation = new Delete();
        $context = new Context();

        $this->processor->process($data, $operation, $context)->willReturn($result)->shouldBeCalled();

        $this->assertSame($result, $this->bulkAwareProcessor->process($data, $operation, $context));
    }
}
