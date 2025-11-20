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

namespace Sylius\Resource\Tests\Symfony\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Resource\Symfony\EventDispatcher\OperationEventHandler;
use Sylius\Resource\Symfony\Routing\RedirectHandlerInterface;
use Sylius\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class OperationEventHandlerTest extends TestCase
{
    private RedirectHandlerInterface $redirectHandler;

    private FlashHelperInterface $flashHelper;

    private OperationEventHandler $operationEventHandler;

    protected function setUp(): void
    {
        $this->redirectHandler = $this->createMock(RedirectHandlerInterface::class);
        $this->flashHelper = $this->createMock(FlashHelperInterface::class);
        $this->operationEventHandler = new OperationEventHandler($this->redirectHandler, $this->flashHelper);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(OperationEventHandler::class, $this->operationEventHandler);
    }

    public function testItReturnsNullWhenPreProcessEventIsNotStopped(): void
    {
        $event = new OperationEvent();
        $context = new Context();

        $this->flashHelper->expects($this->never())->method('addFlashFromEvent');
        $this->redirectHandler->expects($this->never())->method('redirectToResource');
        $this->redirectHandler->expects($this->never())->method('redirectToOperation');

        $result = $this->operationEventHandler->handlePreProcessEvent($event, $context);

        $this->assertNull($result);
    }

    public function testItThrowsAnHttpExceptionWhenPreProcessEventIsStoppedAndRequestFormatIsNotHtml(): void
    {
        $event = new OperationEvent();
        $event->stop(message: 'What the hell is going on?', errorCode: 666);

        $context = new Context();

        try {
            $this->operationEventHandler->handlePreProcessEvent($event, $context);
            $this->fail('Expected HttpException to be thrown');
        } catch (HttpException $e) {
            $this->assertSame('What the hell is going on?', $e->getMessage());
            $this->assertSame(666, $e->getStatusCode());
        }
    }

    public function testItReturnsResponseFromPreProcessEventWhenItHasOneAndRequestFormatIsHtml(): void
    {
        $response = $this->createMock(Response::class);
        $request = $this->createMock(Request::class);

        $event = new OperationEvent();
        $event->stop(message: 'What the hell is going on?', errorCode: 666);
        $event->setResponse($response);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $this->flashHelper->expects($this->once())->method('addFlashFromEvent')->with($event, $context);

        $result = $this->operationEventHandler->handlePreProcessEvent($event, $context);

        $this->assertSame($response, $result);
    }

    public function testItDoesNotReturnsResponseFromPreProcessEventWhenRequestFormatIsNotHtml(): void
    {
        $response = $this->createMock(Response::class);
        $request = $this->createMock(Request::class);

        $event = new OperationEvent();
        $event->stop(message: 'What the hell is going on?', errorCode: 666);
        $event->setResponse($response);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('json');

        try {
            $this->operationEventHandler->handlePreProcessEvent($event, $context);
            $this->fail('Expected HttpException to be thrown');
        } catch (HttpException $e) {
            $this->assertSame('What the hell is going on?', $e->getMessage());
            $this->assertSame(666, $e->getStatusCode());
        }
    }

    public function testItCanRedirectToResourceWhenPreProcessEventIsStoppedAndHasNoResponseAndOperationIsAnHttpOperation(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $redirectResponse = $this->createMock(RedirectResponse::class);

        $event = new OperationEvent($data);
        $event->stop(message: 'What the hell is going on?', errorCode: 666);

        $operation = new Update();
        $event->setArgument('operation', $operation);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $this->flashHelper->expects($this->once())->method('addFlashFromEvent')->with($event, $context);
        $this->redirectHandler
            ->expects($this->once())
            ->method('redirectToResource')
            ->with($data, $operation, $request)
            ->willReturn($redirectResponse);

        $result = $this->operationEventHandler->handlePreProcessEvent($event, $context);

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }

    public function testItCanRedirectToOperationWhenPreProcessEventIsStoppedAndHasNoResponseAndOperationIsAnHttpOperation(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $redirectResponse = $this->createMock(RedirectResponse::class);

        $event = new OperationEvent($data);
        $event->stop(message: 'What the hell is going on?', errorCode: 666);

        $operation = new Update();
        $event->setArgument('operation', $operation);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $this->flashHelper->expects($this->once())->method('addFlashFromEvent')->with($event, $context);
        $this->redirectHandler
            ->expects($this->once())
            ->method('redirectToOperation')
            ->with($data, $operation, $request, 'index')
            ->willReturn($redirectResponse);

        $result = $this->operationEventHandler->handlePreProcessEvent($event, $context, 'index');

        $this->assertInstanceOf(RedirectResponse::class, $result);
    }

    public function testItReturnsNullWhenPreProcessEventIsStoppedAndHasNoResponseAndOperationIsNotAnHttpOperation(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(Operation::class);

        $event = new OperationEvent($data);
        $event->stop(message: 'What the hell is going on?', errorCode: 666);
        $event->setArgument('operation', $operation);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $this->flashHelper->expects($this->once())->method('addFlashFromEvent')->with($event, $context);

        $result = $this->operationEventHandler->handlePreProcessEvent($event, $context);

        $this->assertNull($result);
    }

    public function testItReturnsPostProcessEventResponseWhenRequestFormatIsHtml(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);

        $event = new OperationEvent($data);
        $event->setResponse($response);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $result = $this->operationEventHandler->handlePostProcessEvent($event, $context);

        $this->assertSame($response, $result);
    }

    public function testItReturnsNullForPostProcessEventWhenRequestFormatIsHtmlButEventHasNoResponse(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);

        $event = new OperationEvent($data);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('html');

        $result = $this->operationEventHandler->handlePostProcessEvent($event, $context);

        $this->assertNull($result);
    }

    public function testItReturnsNullForPostProcessEventWhenRequestFormatIsNotHtml(): void
    {
        $data = new \stdClass();
        $request = $this->createMock(Request::class);

        $event = new OperationEvent($data);

        $context = new Context(new RequestOption($request));

        $request->method('getRequestFormat')->willReturn('json');

        $result = $this->operationEventHandler->handlePostProcessEvent($event, $context);

        $this->assertNull($result);
    }
}
