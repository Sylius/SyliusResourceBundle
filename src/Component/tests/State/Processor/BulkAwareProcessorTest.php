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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Api\Delete;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\State\Processor\BulkAwareProcessor;
use Sylius\Resource\State\ProcessorInterface;

final class BulkAwareProcessorTest extends TestCase
{
    private BulkAwareProcessor $bulkAwareProcessor;

    private ProcessorInterface|MockObject $processor;

    protected function setUp(): void
    {
        $this->processor = $this->createMock(ProcessorInterface::class);
        $this->bulkAwareProcessor = new BulkAwareProcessor($this->processor);
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

        $this->processor->expects($this->exactly(2))
            ->method('process')
            ->willReturnCallback(function () {
                return null;
            });

        $data = [$firstItem, $secondItem];

        $this->bulkAwareProcessor->process($data, $operation, $context);
    }

    public function testItCallsDecoratedProcessorForDataForOtherOperationThanBulkOne(): void
    {
        $data = new \stdClass();
        $result = new \stdClass();
        $operation = new Delete();
        $context = new Context();

        $this->processor->expects($this->once())
            ->method('process')
            ->with($data, $operation, $context)
            ->willReturn($result);

        $this->assertSame($result, $this->bulkAwareProcessor->process($data, $operation, $context));
    }
}
