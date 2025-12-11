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

namespace Sylius\Component\Resource\tests\State\Processor;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\State\Processor\WriteProcessor;
use Sylius\Resource\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Response;

final class WriteProcessorTest extends TestCase
{
    private MockObject|ProcessorInterface $processor;

    private MockObject|ProcessorInterface $locatorProcessor;

    private WriteProcessor $writeProcessor;

    protected function setUp(): void
    {
        $this->processor = $this->createMock(ProcessorInterface::class);
        $this->locatorProcessor = $this->createMock(ProcessorInterface::class);

        $this->writeProcessor = new WriteProcessor(
            $this->processor,
            $this->locatorProcessor,
        );
    }

    /** @test */
    public function it_calls_locator_processor_to_write_data(): void
    {
        $data = ['foo' => 'fighters'];
        $operation = new Create(processor: 'App\Processor');
        $context = new Context();
        $processedData = new \stdClass();
        $response = new Response();

        $this->locatorProcessor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($processedData);

        $this->processor->expects($this->once())->method('process')->with($processedData, $operation, $context)->willReturn($response);

        $result = $this->writeProcessor->process($data, $operation, $context);
        $this->assertEquals($response, $result);
    }

    /** @test */
    public function it_does_not_call_locator_processor_when_data_is_a_response(): void
    {
        $data = new Response();
        $operation = new Create(processor: 'App\Processor');
        $context = new Context();

        $this->locatorProcessor->expects($this->never())->method('process');

        $this->processor->expects($this->once())->method('process')->willReturn($data);

        $this->writeProcessor->process($data, $operation, $context);
    }

    /** @test */
    public function it_does_not_call_locator_processor_when_operation_cannot_be_written(): void
    {
        $data = ['foo' => 'fighters'];
        $operation = new Create(processor: 'App\Processor', write: false);
        $context = new Context();

        $this->locatorProcessor->expects($this->never())->method('process');

        $this->processor->expects($this->once())->method('process')->willReturn($data);

        $this->writeProcessor->process($data, $operation, $context);
    }

    /** @test */
    public function it_does_not_call_locator_processor_when_operation_has_no_processor(): void
    {
        $data = ['foo' => 'fighters'];
        $operation = new Create(processor: null);
        $context = new Context();

        $this->locatorProcessor->expects($this->never())->method('process');

        $this->processor->expects($this->once())->method('process')->willReturn($data);

        $this->writeProcessor->process($data, $operation, $context);
    }
}
