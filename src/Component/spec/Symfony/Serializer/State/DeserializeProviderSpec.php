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
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Serializer\State\DeserializeProvider;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

final class DeserializeProviderTest extends TestCase
{
    use ProphecyTrait;

    private ProviderInterface|ObjectProphecy $decorated;

    private SerializerInterface|ObjectProphecy $serializer;

    private DeserializeProvider $deserializableProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProviderInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->deserializableProvider = new DeserializeProvider(
            $this->decorated->reveal(),
            $this->serializer->reveal(),
        );
    }

    private function createRequestMock(bool $isMethodSafe = false, string $format = 'json', mixed $content = ['food' => 'fighters'], string $method = 'POST'): Request
    {
        $request = $this->prophesize(Request::class);
        $request->attributes = new ParameterBag();
        $request->isMethodSafe()->willReturn($isMethodSafe);
        $request->getRequestFormat()->willReturn($format);
        $request->getContent()->willReturn($content);
        $request->getMethod()->willReturn($method);

        return $request->reveal();
    }

    private function createOperationMock(
        ResourceMetadata|false|null $resource = false,
        ?bool $canDeserialize = null,
        ?array $denormalizationContext = [],
    ): HttpOperation {
        $operation = $this->prophesize(HttpOperation::class);

        if ($resource === false) {
            $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        } else {
            $operation->getResource()->willReturn($resource);
        }

        $operation->canDeserialize()->willReturn($canDeserialize);
        $operation->getDenormalizationContext()->willReturn($denormalizationContext);

        return $operation->reveal();
    }

    public function testItDeserializesData(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock();
        $data = $this->prophesize(\stdClass::class);
        $context = new Context(new RequestOption($request));

        $this->decorated->provide($operation, $context)->willReturn($data)->shouldBeCalled();
        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', ['object_to_populate' => $data])->willReturn($data)->shouldBeCalled();

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDeserializesDataWithDenormalizationContext(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(denormalizationContext: ['groups' => ['dummy:write']]);
        $data = $this->prophesize(\stdClass::class);
        $context = new Context(new RequestOption($request));

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', ['groups' => ['dummy:write']])->willReturn($data)->shouldBeCalled();

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfOperationCannotBeDeserialized(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(canDeserialize: false);
        $context = new Context(new RequestOption($request));

        $this->serializer->deserialize(\Prophecy\Argument::cetera())->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfOperationHasNoResource(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(resource: null, canDeserialize: true);
        $context = new Context(new RequestOption($request));
        $data = new \stdClass();

        $this->decorated->provide($operation, $context)->willReturn($data);
        $this->serializer->deserialize(\Prophecy\Argument::cetera())->shouldNotBeCalled();

        $result = $this->deserializableProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItDoesNothingIfRequestFormatIsHtml(): void
    {
        $request = $this->createRequestMock(format: 'html');
        $operation = $this->createOperationMock(canDeserialize: true);
        $context = new Context(new RequestOption($request));

        $this->serializer->deserialize(\Prophecy\Argument::cetera())->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfRequestMethodIsSafe(): void
    {
        $request = $this->createRequestMock(isMethodSafe: true);
        $operation = $this->createOperationMock(canDeserialize: true);
        $context = new Context(new RequestOption($request));

        $this->serializer->deserialize(\Prophecy\Argument::cetera())->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfOperationIsADeleteOne(): void
    {
        $request = $this->createRequestMock();
        $operation = (new Delete())->withResource(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $context = new Context(new RequestOption($request));

        $this->serializer->deserialize(\Prophecy\Argument::cetera())->shouldNotBeCalled();

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItThrowsAnExceptionWhenSerializerIsNotAvailable(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(canDeserialize: true);
        $context = new Context(new RequestOption($request));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".');

        $deserializableProvider = new DeserializeProvider($this->decorated->reveal(), null);
        $deserializableProvider->provide($operation, $context);
    }

    public function testItReturnsDataWhenOperationIsNotHttpOperation(): void
    {
        $operation = $this->prophesize(\Sylius\Resource\Metadata\Operation::class);
        $data = new \stdClass();
        $context = new Context();

        $this->decorated->provide($operation->reveal(), $context)->willReturn($data)->shouldBeCalled();
        $this->serializer->deserialize(\Prophecy\Argument::cetera())->shouldNotBeCalled();

        $result = $this->deserializableProvider->provide($operation->reveal(), $context);

        $this->assertSame($data, $result);
    }

    public function testItReturnsDataWhenRequestIsNull(): void
    {
        $operation = $this->prophesize(HttpOperation::class);
        $data = new \stdClass();
        $context = new Context();

        $this->decorated->provide($operation->reveal(), $context)->willReturn($data)->shouldBeCalled();
        $this->serializer->deserialize(\Prophecy\Argument::cetera())->shouldNotBeCalled();

        $result = $this->deserializableProvider->provide($operation->reveal(), $context);

        $this->assertSame($data, $result);
    }
}
