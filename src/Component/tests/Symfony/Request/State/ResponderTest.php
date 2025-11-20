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
use Psr\Container\ContainerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\ResponderInterface;
use Sylius\Resource\Symfony\Request\State\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResponderTest extends TestCase
{
    private ContainerInterface $locator;

    private Responder $responder;

    protected function setUp(): void
    {
        $this->locator = $this->createMock(ContainerInterface::class);
        $this->responder = new Responder($this->locator);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(Responder::class, $this->responder);
    }

    public function testItUsesHtmlResponderOnHtmlFormat(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $htmlResponder = $this->createMock(ResponderInterface::class);
        $operation = $this->createMock(HttpOperation::class);
        $response = $this->createMock(Response::class);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $this->locator->method('has')->with('sylius.state_responder.html')->willReturn(true);
        $this->locator->method('get')->with('sylius.state_responder.html')->willReturn($htmlResponder);

        $htmlResponder
            ->expects($this->once())
            ->method('respond')
            ->with($data, $operation, $context)
            ->willReturn($response);

        $this->responder->respond($data, $operation, $context);
    }

    public function testItUsesApiResponderOnJsonFormat(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $apiResponder = $this->createMock(ResponderInterface::class);
        $operation = $this->createMock(HttpOperation::class);
        $response = $this->createMock(Response::class);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('json');

        $this->locator->method('has')->with('sylius.state_responder.api')->willReturn(true);
        $this->locator->method('get')->with('sylius.state_responder.api')->willReturn($apiResponder);

        $apiResponder
            ->expects($this->once())
            ->method('respond')
            ->with($data, $operation, $context)
            ->willReturn($response);

        $this->responder->respond($data, $operation, $context);
    }

    public function testItThrowAnExceptionWhenHtmlResponderWasNotFound(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $this->locator->method('has')->with('sylius.state_responder.html')->willReturn(false);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Responder "sylius.state_responder.html" was not found but it should.');

        $this->responder->respond($data, $operation, $context);
    }

    public function testItThrowAnExceptionWhenJsonResponderWasNotFound(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('json');

        $this->locator->method('has')->with('sylius.state_responder.api')->willReturn(false);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Responder "sylius.state_responder.api" was not found but it should.');

        $this->responder->respond($data, $operation, $context);
    }
}
