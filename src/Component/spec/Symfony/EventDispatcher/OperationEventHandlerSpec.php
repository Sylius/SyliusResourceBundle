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

namespace spec\Sylius\Component\Resource\Symfony\EventDispatcher;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEvent;
use Sylius\Component\Resource\Symfony\EventDispatcher\OperationEventHandler;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class OperationEventHandlerSpec extends ObjectBehavior
{
    function let(RedirectHandlerInterface $redirectHandler): void
    {
        $this->beConstructedWith($redirectHandler);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OperationEventHandler::class);
    }

    function it_throws_an_http_exception_when_pre_process_event_is_stopped_and_request_format_is_not_html(): void
    {
        $event = new OperationEvent();
        $event->stop(message: 'What the hell is going on?', errorCode: 666);

        $context = new Context();

        $this->shouldThrow(new HttpException(666, 'What the hell is going on?'))
            ->during('handlePreProcessEvent', [$event, $context])
        ;
    }

    function it_returns_response_from_pre_process_event_when_it_has_one_and_request_format_is_html(
        Request $request,
        Response $response,
    ): void {
        $event = new OperationEvent();
        $event->stop(message: 'What the hell is going on?', errorCode: 666);
        $event->setResponse($response->getWrappedObject());

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getRequestFormat()->willReturn('html');

        $this->handlePreProcessEvent($event, $context)->shouldReturn($response);
    }

    function it_does_not_returns_response_from_pre_process_event_when_request_format_is_not_html(
        Request $request,
        Response $response,
    ): void {
        $event = new OperationEvent();
        $event->stop(message: 'What the hell is going on?', errorCode: 666);
        $event->setResponse($response->getWrappedObject());

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getRequestFormat()->willReturn('json');

        $this->shouldThrow(new HttpException(666, 'What the hell is going on?'))
            ->during('handlePreProcessEvent', [$event, $context])
        ;
    }

    function it_can_redirect_to_resource_when_pre_process_event_is_stopped_and_has_no_response_and_operation_is_an_http_operation(
        Request $request,
        \stdClass $data,
        RedirectHandlerInterface $redirectHandler,
        RedirectResponse $response,
    ): void {
        $event = new OperationEvent($data);
        $event->stop(message: 'What the hell is going on?', errorCode: 666);

        $operation = new Update();

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $event->setArgument('operation', $operation);

        $request->getRequestFormat()->willReturn('html');

        $redirectHandler->redirectToResource($data, $operation, $request)->willReturn($response)->shouldBeCalled();

        $this->handlePreProcessEvent($event, $context)->shouldHaveType(RedirectResponse::class);
    }

    function it_can_redirect_to_operation_when_pre_process_event_is_stopped_and_has_no_response_and_operation_is_an_http_operation(
        Request $request,
        \stdClass $data,
        RedirectHandlerInterface $redirectHandler,
        RedirectResponse $response,
    ): void {
        $event = new OperationEvent($data);
        $event->stop(message: 'What the hell is going on?', errorCode: 666);

        $operation = new Update();

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $event->setArgument('operation', $operation);

        $request->getRequestFormat()->willReturn('html');

        $redirectHandler->redirectToOperation($data, $operation, $request, 'index')->willReturn($response)->shouldBeCalled();

        $this->handlePreProcessEvent($event, $context, 'index')->shouldHaveType(RedirectResponse::class);
    }

    function it_returns_null_when_pre_process_event_is_stopped_and_has_no_response_and_operation_is_not_an_http_operation(
        Request $request,
        \stdClass $data,
        Operation $operation,
    ): void {
        $event = new OperationEvent($data);
        $event->stop(message: 'What the hell is going on?', errorCode: 666);
        $event->setArgument('operation', $operation->getWrappedObject());

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getRequestFormat()->willReturn('html');

        $this->handlePreProcessEvent($event, $context)->shouldReturn(null);
    }

    function it_returns_post_process_event_response_when_request_format_is_html(
        Request $request,
        \stdClass $data,
        Response $response,
    ): void {
        $event = new OperationEvent($data);
        $event->setResponse($response->getWrappedObject());

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getRequestFormat()->willReturn('html');

        $this->handlePostProcessEvent($event, $context)->shouldReturn($response);
    }

    function it_returns_null_for_post_process_event_when_request_format_is_html_but_event_has_no_response(
        Request $request,
        \stdClass $data,
    ): void {
        $event = new OperationEvent($data);

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getRequestFormat()->willReturn('html');

        $this->handlePostProcessEvent($event, $context)->shouldReturn(null);
    }

    function it_returns_null_for_post_process_event_when_request_format_is_not_html(
        Request $request,
        \stdClass $data,
    ): void {
        $event = new OperationEvent($data);

        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getRequestFormat()->willReturn('json');

        $this->handlePostProcessEvent($event, $context)->shouldReturn(null);
    }
}
