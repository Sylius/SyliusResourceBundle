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

namespace Sylius\Component\Resource\tests\Symfony\Serializer\State;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\Serializer\State\SerializeProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializeProcessorTest extends TestCase
{
    private ProcessorInterface|MockObject $processor;

    private SerializerInterface|MockObject $serializer;

    private SerializeProcessor $serializeProcessor;

    protected function setUp(): void
    {
        $this->processor = $this->createMock(ProcessorInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->serializeProcessor = new SerializeProcessor(
            $this->processor,
            $this->serializer,
        );
    }

    private function createRequestMock(string $format = 'json'): Request
    {
        $request = $this->createMock(Request::class);
        $request->method('getRequestFormat')->willReturn($format);

        return $request;
    }

    private function createOperationMock(?bool $canSerialize = null, array $normalizationContext = []): HttpOperation
    {
        $operation = $this->createMock(HttpOperation::class);
        $operation->method('canSerialize')->willReturn($canSerialize);
        $operation->method('getNormalizationContext')->willReturn($normalizationContext);

        return $operation;
    }

    public function testItSerializesDataToTheRequestedFormat(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock();
        $data = new \stdClass();
        $context = new Context(new RequestOption($request));

        $this->processor->expects($this->once())->method('process')->with('serialized_data', $operation, $context)->willReturn('serialized_data');
        $this->serializer->expects($this->once())->method('serialize')->with($data, 'json', [])->willReturn('serialized_data');

        $result = $this->serializeProcessor->process($data, $operation, $context);

        $this->assertSame('serialized_data', $result);
    }

    public function testItSerializesDataToTheRequestedFormatWithNormalizationContext(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(normalizationContext: ['groups' => ['dummy:read']]);
        $data = new \stdClass();
        $context = new Context(new RequestOption($request));

        $this->processor->expects($this->once())->method('process')->with('serialized_data', $operation, $context)->willReturn('serialized_data');
        $this->serializer->expects($this->once())->method('serialize')->with($data, 'json', ['groups' => ['dummy:read']])->willReturn('serialized_data');

        $result = $this->serializeProcessor->process($data, $operation, $context);

        $this->assertSame('serialized_data', $result);
    }

    public function testItDoesNothingWhenFormatIsHtml(): void
    {
        $request = $this->createRequestMock('html');
        $operation = $this->createOperationMock();
        $data = new \stdClass();
        $context = new Context(new RequestOption($request));

        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($data);
        $this->serializer->expects($this->never())->method('serialize');

        $result = $this->serializeProcessor->process($data, $operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItThrowsAnExceptionWhenSerializerIsNotAvailable(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock();
        $data = new \stdClass();
        $context = new Context(new RequestOption($request));

        $serializeProcessor = new SerializeProcessor($this->processor, null);
        $this->processor->expects($this->never())->method('process');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".');

        $serializeProcessor->process($data, $operation, $context);
    }

    public function testItDoesNothingIfOperationCannotBeSerialized(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(canSerialize: false);
        $data = new \stdClass();
        $context = new Context(new RequestOption($request));

        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($data);
        $this->serializer->expects($this->never())->method('serialize');

        $result = $this->serializeProcessor->process($data, $operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItProcessesDataWithoutSerializationWhenRequestIsNull(): void
    {
        $operation = $this->createOperationMock();
        $data = new \stdClass();
        $context = new Context();

        $this->processor->expects($this->once())->method('process')->with($data, $operation, $context)->willReturn($data);
        $this->serializer->expects($this->never())->method('serialize');

        $result = $this->serializeProcessor->process($data, $operation, $context);

        $this->assertSame($data, $result);
    }
}
