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

namespace Tests\Sylius\Resource\Symfony\Request\State;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\Request\State\TwigResponder;
use Sylius\Resource\Symfony\Routing\RedirectHandlerInterface;
use Sylius\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

final class TwigResponderTest extends TestCase
{
    private TwigResponder $twigResponder;

    private RedirectHandlerInterface $redirectHandler;

    private ContextFactoryInterface $contextFactory;

    private Environment $twig;

    protected function setUp(): void
    {
        $this->redirectHandler = $this->createMock(RedirectHandlerInterface::class);
        $this->contextFactory = $this->createMock(ContextFactoryInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->twigResponder = new TwigResponder($this->redirectHandler, $this->contextFactory, $this->twig);
    }

    public function testIsInitializable(): void
    {
        $this->assertInstanceOf(TwigResponder::class, $this->twigResponder);
    }

    public function testReturnsAResponseForResourceShow(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;
        $request->method('isMethodSafe')->willReturn(true);

        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(false);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');
        $operation = (new Show(template: 'book/show.html.twig'))->withResource($resource);

        $this->contextFactory->method('create')->with($data, $operation, $context)->willReturn(['book' => $data]);
        $this->twig->method('render')->with('book/show.html.twig', ['book' => $data])->willReturn('result');

        $response = $this->twigResponder->respond($data, $operation, $context);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testReturnsAResponseForResourceIndex(): void
    {
        $data = new \ArrayObject();
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;
        $request->method('isMethodSafe')->willReturn(true);

        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(true);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', pluralName: 'books');
        $operation = (new Index(template: 'book/index.html.twig'))->withResource($resource);

        $this->contextFactory->method('create')->with($data, $operation, $context)->willReturn(['books' => $data]);
        $this->twig->method('render')->with('book/index.html.twig', ['books' => $data])->willReturn('result');

        $response = $this->twigResponder->respond($data, $operation, $context);
        $this->assertNotNull($response);
    }

    public function testRedirectToRouteAfterCreation(): void
    {
        $data = new \ArrayObject();
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $response = $this->createMock(RedirectResponse::class);

        $data->offsetSet('id', 'xyz');
        $request->attributes = $attributes;

        $request->method('isMethodSafe')->willReturn(false);
        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(true);

        $operation = new Create();
        $this->redirectHandler->method('redirectToResource')->with($data, $operation, $request)->willReturn($response);

        $result = $this->twigResponder->respond($data, $operation, new Context(new RequestOption($request)));
        $this->assertSame($response, $result);
    }

    public function testResponseIsUnprocessableWhenValidationHasFailed(): void
    {
        $data = new \ArrayObject();
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $context = new Context(new RequestOption($request));

        $data->offsetSet('id', 'xyz');
        $request->attributes = $attributes;

        $request->method('isMethodSafe')->willReturn(false);
        $attributes->method('getBoolean')->with('is_valid', true)->willReturn(false);

        $operation = new Create();

        $this->contextFactory->method('create')->with($data, $operation, $context)->willReturn(['books' => $data]);
        $this->twig->method('render')->willReturn('twig_content');

        $response = $this->twigResponder->respond($data, $operation, new Context(new RequestOption($request)));
        $this->assertEquals(422, $response->getStatusCode());
    }
}
