<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Resource\Symfony\Request\State;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Symfony\Request\State\ApiResponder;
use Sylius\Component\Resource\Symfony\Response\ApiHeadersInitiator;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponderSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(new ApiHeadersInitiator());
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ApiResponder::class);
    }

    function it_returns_a_response_with_http_created_for_resource_create(
        Request $request,
        ParameterBag $attributes,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->attributes = $attributes;

        $request->getRequestFormat()->willReturn('json');
        $request->getMimeType('json')->willReturn('application/json');

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new Resource(alias: 'app.book', name: 'book');
        $operation = (new Create())->withResource($resource);

        $response = $this->respond('serialized_data', $operation, $context);
        $response->shouldHaveType(Response::class);
        $response->getStatusCode()->shouldReturn(Response::HTTP_CREATED);
    }

    function it_returns_a_response_with_http_no_content_for_resource_update(
        Request $request,
        ParameterBag $attributes,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->attributes = $attributes;

        $request->getRequestFormat()->willReturn('json');
        $request->getMimeType('json')->willReturn('application/json');

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new Resource(alias: 'app.book', name: 'book');
        $operation = (new Update())->withResource($resource);

        $response = $this->respond('serialized_data', $operation, $context);
        $response->shouldHaveType(Response::class);
        $response->getStatusCode()->shouldReturn(Response::HTTP_NO_CONTENT);
    }

    function it_returns_a_response_with_http_no_content_for_resource_delete(
        Request $request,
        ParameterBag $attributes,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->attributes = $attributes;

        $request->getRequestFormat()->willReturn('json');
        $request->getMimeType('json')->willReturn('application/json');

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new Resource(alias: 'app.book', name: 'book');
        $operation = (new Delete())->withResource($resource);

        $response = $this->respond('serialized_data', $operation, $context);
        $response->shouldHaveType(Response::class);
        $response->getStatusCode()->shouldReturn(Response::HTTP_NO_CONTENT);
    }

    function it_returns_a_response_with_http_unprocessable_entity_for_invalid_resource(
        Request $request,
        ParameterBag $attributes,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->attributes = $attributes;

        $request->getRequestFormat()->willReturn('json');
        $request->getMimeType('json')->willReturn('application/json');

        $attributes->getBoolean('is_valid', true)->willReturn(false)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new Resource(alias: 'app.book', name: 'book');
        $operation = (new Create())->withResource($resource);

        $response = $this->respond('serialized_data', $operation, $context);
        $response->shouldHaveType(Response::class);
        $response->getStatusCode()->shouldReturn(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
