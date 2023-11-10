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

namespace State\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Resource\src\State\Provider\DeserializeProvider;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

final class DeserializeProviderTest extends TestCase
{
    use ProphecyTrait;

    /** @var ProviderInterface|ObjectProphecy */
    private $decorated;

    /** @var SerializerInterface|ObjectProphecy */
    private $serializer;

    /** @var DeserializeProvider */
    private $deserializableProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProviderInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->deserializableProvider = new DeserializeProvider(
            $this->decorated->reveal(),
            $this->serializer->reveal(),
        );
    }

    /** @test */
    public function it_deserializes_data(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->getDenormalizationContext()->willReturn(null)->shouldBeCalled();

        $request->isMethodSafe()->willReturn(false)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();
        $request->getContent()->willReturn(['food' => 'fighters'])->shouldBeCalled();
        $request->getMethod()->willReturn('POST')->shouldBeCalled();

        $operation->canDeserialize()->willReturn(null)->shouldBeCalled();
        $operation->getDenormalizationContext()->willReturn([])->shouldBeCalled();

        $this->decorated->provide($operation->reveal(), $context)->willReturn($data)->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', ['object_to_populate' => $data])->willReturn($data)->shouldBeCalled();

        $this->deserializableProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_deserializes_data_with_denormalization_context(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $request->isMethodSafe()->willReturn(false)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();
        $request->getContent()->willReturn(['food' => 'fighters'])->shouldBeCalled();
        $request->getMethod()->willReturn('POST')->shouldBeCalled();

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->canDeserialize()->willReturn(null)->shouldBeCalled();
        $operation->getDenormalizationContext()->willReturn(['groups' => ['dummy:write']]);

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', ['groups' => ['dummy:write']])->willReturn($data)->shouldBeCalled();

        $this->deserializableProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_cannot_be_deserialized(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->canDeserialize()->willReturn(false)->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_has_no_resource(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $operation->getResource()->willReturn(null);
        $operation->canDeserialize()->willReturn(true)->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_if_request_format_is_html(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();
        $request->getContent()->willReturn(['food' => 'fighters']);

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->canDeserialize()->willReturn(true)->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_if_request_method_is_safe(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->canDeserialize()->willReturn(true)->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation->reveal(), $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_is_a_delete_one(): void
    {
        $request = $this->prophesize(Request::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $request->isMethodSafe()->willReturn(false)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $operation = (new Delete())->withResource(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation, $context);
    }

    /** @test */
    public function it_throws_an_exception_when_serializer_is_not_available(): void
    {
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $context = new Context(new RequestOption($request->reveal()));

        $request->attributes = new ParameterBag();

        $request->isMethodSafe()->willReturn(false)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->canDeserialize()->willReturn(true)->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldNotBeCalled();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".');

        $this->deserializableProvider = new DeserializeProvider($this->decorated->reveal(), null);
        $this->deserializableProvider->provide($operation->reveal(), $context);
    }
}
