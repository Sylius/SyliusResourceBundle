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

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\Serializer\State\SerializeProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

final class SerializeProcessorTest extends TestCase
{
    use ProphecyTrait;

    private ProcessorInterface|ObjectProphecy $processor;

    private SerializerInterface|ObjectProphecy $serializer;

    private SerializeProcessor $serializeProcessor;

    protected function setUp(): void
    {
        $this->processor = $this->prophesize(ProcessorInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->serializeProcessor = new SerializeProcessor(
            $this->processor->reveal(),
            $this->serializer->reveal(),
        );
    }

    private function createRequestMock(string $format = 'json'): Request
    {
        $request = $this->prophesize(Request::class);
        $request->getRequestFormat()->willReturn($format);

        return $request->reveal();
    }

    private function createOperationMock(?bool $canSerialize = null, array $normalizationContext = []): HttpOperation
    {
        $operation = $this->prophesize(HttpOperation::class);
        $operation->canSerialize()->willReturn($canSerialize);
        $operation->getNormalizationContext()->willReturn($normalizationContext);

        return $operation->reveal();
    }

    public function testItSerializesDataToTheRequestedFormat(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock();
        $data = $this->prophesize(\stdClass::class);
        $context = new Context(new RequestOption($request));

        $this->processor->process('serialized_data', $operation, $context)->willReturn('serialized_data')->shouldBeCalled();
        $this->serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldBeCalled();

        $result = $this->serializeProcessor->process($data, $operation, $context);

        Assert::eq($result, 'serialized_data');
    }

    public function testItSerializesDataToTheRequestedFormatWithNormalizationContext(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(normalizationContext: ['groups' => ['dummy:read']]);
        $data = $this->prophesize(\stdClass::class);
        $context = new Context(new RequestOption($request));

        $this->processor->process('serialized_data', $operation, $context)->willReturn('serialized_data');
        $this->serializer->serialize($data, 'json', ['groups' => ['dummy:read']])->willReturn('serialized_data')->shouldBeCalled();

        $result = $this->serializeProcessor->process($data, $operation, $context);

        Assert::eq($result, 'serialized_data');
    }

    public function testItDoesNothingWhenFormatIsHtml(): void
    {
        $request = $this->createRequestMock('html');
        $operation = $this->createOperationMock();
        $data = $this->prophesize(\stdClass::class);
        $context = new Context(new RequestOption($request));

        $this->processor->process($data, $operation, $context)->willReturn($data);
        $this->serializer->serialize(Argument::cetera())->shouldNotBeCalled();

        $result = $this->serializeProcessor->process($data, $operation, $context);

        Assert::eq($result, $data->reveal());
    }

    public function testItThrowsAnExceptionWhenSerializerIsNotAvailable(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock();
        $data = $this->prophesize(\stdClass::class);
        $context = new Context(new RequestOption($request));

        $serializeProcessor = new SerializeProcessor($this->processor->reveal(), null);
        $this->processor->process($data, $operation, $context)->willReturn($data);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".');

        $serializeProcessor->process($data, $operation, $context);
    }

    public function testItDoesNothingIfOperationCannotBeSerialized(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(canSerialize: false);
        $data = $this->prophesize(\stdClass::class);
        $context = new Context(new RequestOption($request));

        $this->processor->process($data, $operation, $context)->willReturn($data);
        $this->serializer->serialize(Argument::cetera())->shouldNotBeCalled();

        $result = $this->serializeProcessor->process($data, $operation, $context);

        Assert::eq($result, $data->reveal());
    }

    public function testItProcessesDataWithoutSerializationWhenRequestIsNull(): void
    {
        $operation = $this->createOperationMock();
        $data = $this->prophesize(\stdClass::class);
        $context = new Context();

        $this->processor->process($data, $operation, $context)->willReturn($data)->shouldBeCalled();
        $this->serializer->serialize(Argument::cetera())->shouldNotBeCalled();

        $result = $this->serializeProcessor->process($data, $operation, $context);

        Assert::eq($result, $data->reveal());
    }
}
