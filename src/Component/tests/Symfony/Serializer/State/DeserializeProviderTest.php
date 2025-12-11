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
    private ProviderInterface|MockObject $decorated;

    private SerializerInterface|MockObject $serializer;

    private DeserializeProvider $deserializableProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ProviderInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->deserializableProvider = new DeserializeProvider(
            $this->decorated,
            $this->serializer,
        );
    }

    private function createRequestMock(bool $isMethodSafe = false, string $format = 'json', mixed $content = ['food' => 'fighters'], string $method = 'POST'): Request
    {
        $request = $this->createMock(Request::class);
        $request->attributes = new ParameterBag();
        $request->method('isMethodSafe')->willReturn($isMethodSafe);
        $request->method('getRequestFormat')->willReturn($format);
        $request->method('getContent')->willReturn($content);
        $request->method('getMethod')->willReturn($method);

        return $request;
    }

    private function createOperationMock(
        ResourceMetadata|false|null $resource = false,
        ?bool $canDeserialize = null,
        ?array $denormalizationContext = [],
    ): HttpOperation {
        $operation = $this->createMock(HttpOperation::class);

        if ($resource === false) {
            $operation->method('getResource')->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        } else {
            $operation->method('getResource')->willReturn($resource);
        }

        $operation->method('canDeserialize')->willReturn($canDeserialize);
        $operation->method('getDenormalizationContext')->willReturn($denormalizationContext);

        return $operation;
    }

    public function testItDeserializesData(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock();
        $data = new \stdClass();
        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())->method('provide')->with($operation, $context)->willReturn($data);
        $this->serializer->expects($this->once())->method('deserialize')->with(['food' => 'fighters'], 'App\Resource', 'json', ['object_to_populate' => $data])->willReturn($data);

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDeserializesDataWithDenormalizationContext(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(denormalizationContext: ['groups' => ['dummy:write']]);
        $data = new \stdClass();
        $context = new Context(new RequestOption($request));

        $this->serializer->expects($this->once())->method('deserialize')->with(['food' => 'fighters'], 'App\Resource', 'json', ['groups' => ['dummy:write']])->willReturn($data);

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfOperationCannotBeDeserialized(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(canDeserialize: false);
        $context = new Context(new RequestOption($request));

        $this->serializer->expects($this->never())->method('deserialize');

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfOperationHasNoResource(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(resource: null, canDeserialize: true);
        $context = new Context(new RequestOption($request));
        $data = new \stdClass();

        $this->decorated->method('provide')->with($operation, $context)->willReturn($data);
        $this->serializer->expects($this->never())->method('deserialize');

        $result = $this->deserializableProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItDoesNothingIfRequestFormatIsHtml(): void
    {
        $request = $this->createRequestMock(format: 'html');
        $operation = $this->createOperationMock(canDeserialize: true);
        $context = new Context(new RequestOption($request));

        $this->serializer->expects($this->never())->method('deserialize');

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfRequestMethodIsSafe(): void
    {
        $request = $this->createRequestMock(isMethodSafe: true);
        $operation = $this->createOperationMock(canDeserialize: true);
        $context = new Context(new RequestOption($request));

        $this->serializer->expects($this->never())->method('deserialize');

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItDoesNothingIfOperationIsADeleteOne(): void
    {
        $request = $this->createRequestMock();
        $operation = (new Delete())->withResource(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $context = new Context(new RequestOption($request));

        $this->serializer->expects($this->never())->method('deserialize');

        $this->deserializableProvider->provide($operation, $context);
    }

    public function testItThrowsAnExceptionWhenSerializerIsNotAvailable(): void
    {
        $request = $this->createRequestMock();
        $operation = $this->createOperationMock(canDeserialize: true);
        $context = new Context(new RequestOption($request));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".');

        $deserializableProvider = new DeserializeProvider($this->decorated, null);
        $deserializableProvider->provide($operation, $context);
    }

    public function testItReturnsDataWhenOperationIsNotHttpOperation(): void
    {
        $operation = $this->createMock(\Sylius\Resource\Metadata\Operation::class);
        $data = new \stdClass();
        $context = new Context();

        $this->decorated->expects($this->once())->method('provide')->with($operation, $context)->willReturn($data);
        $this->serializer->expects($this->never())->method('deserialize');

        $result = $this->deserializableProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItReturnsDataWhenRequestIsNull(): void
    {
        $operation = $this->createMock(HttpOperation::class);
        $data = new \stdClass();
        $context = new Context();

        $this->decorated->expects($this->once())->method('provide')->with($operation, $context)->willReturn($data);
        $this->serializer->expects($this->never())->method('deserialize');

        $result = $this->deserializableProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }
}
