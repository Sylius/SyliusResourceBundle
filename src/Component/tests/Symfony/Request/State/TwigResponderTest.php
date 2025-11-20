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
    private Environment $twig;

    private RedirectHandlerInterface $redirectHandler;

    private ContextFactoryInterface $contextFactory;

    private TwigResponder $twigResponder;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->redirectHandler = $this->createMock(RedirectHandlerInterface::class);
        $this->contextFactory = $this->createMock(ContextFactoryInterface::class);
        $this->twigResponder = new TwigResponder($this->redirectHandler, $this->contextFactory, $this->twig);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(TwigResponder::class, $this->twigResponder);
    }

    public function testItReturnsAResponseForResourceShow(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $request->method('isMethodSafe')->willReturn(true);

        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(false);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', name: 'book');
        $operation = (new Show(template: 'book/show.html.twig'))->withResource($resource);

        $this->contextFactory
            ->expects($this->once())
            ->method('create')
            ->with($data, $operation, $context)
            ->willReturn(['book' => $data]);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('book/show.html.twig', ['book' => $data])
            ->willReturn('result');

        $response = $this->twigResponder->respond($data, $operation, $context);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testItReturnsAResponseForResourceIndex(): void
    {
        $data = new \ArrayObject();
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $request->method('isMethodSafe')->willReturn(true);

        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(true);
        $attributes->method('get')->with('form')->willReturn(null);

        $resource = new ResourceMetadata(alias: 'app.book', pluralName: 'books');
        $operation = (new Index(template: 'book/index.html.twig'))->withResource($resource);

        $this->contextFactory
            ->expects($this->once())
            ->method('create')
            ->with($data, $operation, $context)
            ->willReturn(['books' => $data]);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('book/index.html.twig', ['books' => $data])
            ->willReturn('result');

        $this->twigResponder->respond($data, $operation, $context);
    }

    public function testItRedirectToRouteAfterCreation(): void
    {
        $data = new \ArrayObject();
        $data['id'] = 'xyz';

        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $redirectResponse = $this->createMock(RedirectResponse::class);

        $request->attributes = $attributes;

        $request->method('isMethodSafe')->willReturn(false);
        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(true);

        $operation = new Create();

        $this->redirectHandler
            ->expects($this->once())
            ->method('redirectToResource')
            ->with($data, $operation, $request)
            ->willReturn($redirectResponse);

        $result = $this->twigResponder->respond($data, $operation, new Context(new RequestOption($request)));

        $this->assertSame($redirectResponse, $result);
    }

    public function testItResponseIsUnprocessableWhenValidationHasFailed(): void
    {
        $data = new \ArrayObject();
        $data['id'] = 'xyz';

        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $context = new Context(new RequestOption($request));

        $request->attributes = $attributes;

        $request->method('isMethodSafe')->willReturn(false);

        $attributes->expects($this->once())->method('getBoolean')->with('is_valid', true)->willReturn(false);

        $operation = new Create();

        $this->contextFactory
            ->expects($this->once())
            ->method('create')
            ->with($data, $operation, $context)
            ->willReturn(['books' => $data]);

        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with('', ['books' => $data])
            ->willReturn('twig_content');

        $response = $this->twigResponder->respond($data, $operation, new Context(new RequestOption($request)));

        $this->assertSame(422, $response->getStatusCode());
    }
}
