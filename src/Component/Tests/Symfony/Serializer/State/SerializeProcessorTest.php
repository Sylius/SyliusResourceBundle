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

namespace Sylius\Component\Resource\Tests\Symfony\Serializer\State;

use PHPUnit\Framework\TestCase;
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

    private ProcessorInterface|ObjectProphecy $decorated;

    private SerializerInterface|ObjectProphecy $serializer;

    private SerializeProcessor $serializeProcessor;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProcessorInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->serializeProcessor = new SerializeProcessor(
            $this->decorated->reveal(),
            $this->serializer->reveal(),
        );
    }

    /** @test */
    public function it_serializes_data_to_the_requested_format(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process($data, $operation, $context)->willReturn($data);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(null)->shouldBeCalled();
        $operation->getNormalizationContext()->willReturn([]);

        $this->serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldBeCalled();

        $result = $this->serializeProcessor->process($data, $operation->reveal(), $context);

        Assert::eq($result, 'serialized_data');
    }

    /** @test */
    public function it_serializes_data_to_the_requested_format_with_normalization_context(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process($data, $operation, $context)->willReturn($data);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(null)->shouldBeCalled();
        $operation->getNormalizationContext()->willReturn(['groups' => ['dummy:read']]);

        $this->serializer->serialize($data, 'json', ['groups' => ['dummy:read']])->willReturn('serialized_data')->shouldBeCalled();

        $result = $this->serializeProcessor->process($data, $operation->reveal(), $context);

        Assert::eq($result, 'serialized_data');
    }

    /** @test */
    public function it_does_nothing_when_format_is_html(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process($data, $operation, $context)->willReturn($data);

        $request->getRequestFormat()->willReturn('html');

        $this->serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldNotBeCalled();

        $result = $this->serializeProcessor->process($data, $operation->reveal(), $context);

        Assert::eq($result, $data->reveal());
    }

    /** @test */
    public function it_throws_an_exception_when_serializer_is_not_available(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $serializeProcessor = new SerializeProcessor($this->decorated->reveal(), null);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process($data, $operation, $context)->willReturn($data);

        $request->getRequestFormat()->willReturn('json', []);

        $this->serializer->serialize($data, 'json')->willReturn('serialized_data')->shouldNotBeCalled();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".');

        $serializeProcessor->process($data, $operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_cannot_be_serialized(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->process($data, $operation, $context)->willReturn($data);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(false)->shouldBeCalled();

        $this->serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldNotBeCalled();

        $result = $this->serializeProcessor->process($data, $operation->reveal(), $context);

        Assert::eq($result, $data->reveal());
    }
}
