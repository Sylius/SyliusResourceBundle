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

namespace Sylius\Resource\Tests\Symfony\Request\State;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\Request\State\ApiResponder;
use Sylius\Resource\Symfony\Response\ApiHeadersInitiator;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponderTest extends TestCase
{
    private ApiResponder $apiResponder;

    protected function setUp(): void
    {
        $this->apiResponder = new ApiResponder(new ApiHeadersInitiator());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ApiResponder::class, $this->apiResponder);
    }

    public function testItReturnsAResponseWithHttpCreatedForResourceCreate(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $request->method('getRequestFormat')->willReturn('json');
        $request->method('getMimeType')->with('json')->willReturn('application/json');

        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(true);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');
        $operation = (new Create())->withResource($resource);

        $response = $this->apiResponder->respond('serialized_data', $operation, $context);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testItReturnsAResponseWithHttpNoContentForResourceUpdate(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $request->method('getRequestFormat')->willReturn('json');
        $request->method('getMimeType')->with('json')->willReturn('application/json');

        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(true);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');
        $operation = (new Update())->withResource($resource);

        $response = $this->apiResponder->respond('serialized_data', $operation, $context);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testItReturnsAResponseWithHttpNoContentForResourceDelete(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $request->method('getRequestFormat')->willReturn('json');
        $request->method('getMimeType')->with('json')->willReturn('application/json');

        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(true);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');
        $operation = (new Delete())->withResource($resource);

        $response = $this->apiResponder->respond('serialized_data', $operation, $context);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testItReturnsAResponseWithHttpUnprocessableEntityForInvalidResource(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $request->method('getRequestFormat')->willReturn('json');
        $request->method('getMimeType')->with('json')->willReturn('application/json');

        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(false);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');
        $operation = (new Create())->withResource($resource);

        $response = $this->apiResponder->respond('serialized_data', $operation, $context);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
}
