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

namespace spec\Sylius\Component\Resource\Symfony\Request\State;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Symfony\Request\State\TwigResponder;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandlerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

final class TwigResponderSpec extends ObjectBehavior
{
    function let(
        Environment $twig,
        RedirectHandlerInterface $redirectHandler,
        ContextFactoryInterface $contextFactory,
    ): void {
        $this->beConstructedWith($redirectHandler, $contextFactory, $twig);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TwigResponder::class);
    }

    function it_returns_a_response_for_resource_show(
        \stdClass $data,
        Request $request,
        ParameterBag $attributes,
        ContextFactoryInterface $contextFactory,
        Environment $twig,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(false)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');
        $operation = (new Show(template: 'book/show.html.twig'))->withResource($resource);

        $contextFactory->create($data, $operation, $context)->willReturn(['book' => $data]);

        $twig->render('book/show.html.twig', [
            'book' => $data->getWrappedObject(),
        ])->willReturn('result')->shouldBeCalled();

        $response = $this->respond($data, $operation, $context);
        $response->getStatusCode()->shouldReturn(200);
    }

    function it_returns_a_response_for_resource_index(
        \ArrayObject $data,
        Request $request,
        ParameterBag $attributes,
        ContextFactoryInterface $contextFactory,
        Environment $twig,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', pluralName: 'books');
        $operation = (new Index(template: 'book/index.html.twig'))->withResource($resource);

        $contextFactory->create($data, $operation, $context)->willReturn(['books' => $data]);

        $twig->render('book/index.html.twig', [
            'books' => $data->getWrappedObject(),
        ])->willReturn('result')->shouldBeCalled();

        $this->respond($data, $operation, $context);
    }

    function it_redirect_to_route_after_creation(
        \ArrayObject $data,
        Request $request,
        ParameterBag $attributes,
        RedirectHandlerInterface $redirectHandler,
        RedirectResponse $response,
    ): void {
        $data->offsetSet('id', 'xyz');
        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();

        $operation = new Create();

        $redirectHandler->redirectToResource($data, $operation, $request)->willReturn($response);

        $this->respond($data, $operation, new Context(new RequestOption($request->getWrappedObject())))->shouldReturn($response);
    }

    function it_response_is_unprocessable_when_validation_has_failed(
        \ArrayObject $data,
        Request $request,
        ParameterBag $attributes,
        ContextFactoryInterface $contextFactory,
        Environment $twig,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $data->offsetSet('id', 'xyz');
        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(false)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(false)->shouldBeCalled();

        $operation = new Create();

        $contextFactory->create($data, $operation, $context)->willReturn(['books' => $data]);

        $twig->render('', ['books' => $data])->willReturn('twig_content');

        $response = $this->respond($data, $operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->getStatusCode()->shouldReturn(422);
    }
}
