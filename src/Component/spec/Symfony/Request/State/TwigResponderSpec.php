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
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Symfony\Request\State\TwigResponder;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandler;
use Sylius\Component\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class TwigResponderSpec extends ObjectBehavior
{
    function let(Environment $twig, RouterInterface $router, ContextFactoryInterface $contextFactory): void
    {
        $this->beConstructedWith(new RedirectHandler($router->getWrappedObject()), $contextFactory, $twig);
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

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new Resource(alias: 'app.book', name: 'book');
        $operation = (new Show(template: 'book/show.html.twig'))->withResource($resource);

        $contextFactory->create($data, $operation, $context)->willReturn(['book' => $data]);

        $twig->render('book/show.html.twig', [
            'book' => $data->getWrappedObject(),
        ])->willReturn('result')->shouldBeCalled();

        $this->respond($data, $operation, $context);
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

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();
        $attributes->get('form')->willReturn(null);

        $resource = new Resource(alias: 'app.book', pluralName: 'books');
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
        RouterInterface $router,
    ): void {
        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldBeCalled();

        $operation = new Create(redirectToRoute: 'app_dummy_index');

        $router->generate('app_dummy_index', [])->willReturn('/dummies')->shouldBeCalled();

        $response = $this->respond($data, $operation, new Context(new RequestOption($request->getWrappedObject())));
        $response->shouldHaveType(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('/dummies');
    }
}
