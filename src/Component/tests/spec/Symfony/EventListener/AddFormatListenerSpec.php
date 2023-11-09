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

namespace spec\Sylius\Resource\Symfony\EventListener;

use Negotiation\Negotiator;
use PhpSpec\ObjectBehavior;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Symfony\EventListener\AddFormatListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

final class AddFormatListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
    ): void {
        $this->beConstructedWith($operationInitiator, new Negotiator());
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AddFormatListener::class);
    }

    function it_sets_format_from_accept_header(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();
        $request->headers = new ParameterBag(['Accept' => 'application/json']);

        $request->getFormat('application/json')->willReturn('json')->shouldBeCalled();

        $request->setRequestFormat('json')->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_sets_format_from_request(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag(['_format' => 'json']);
        $request->headers = new ParameterBag();

        $request->getRequestFormat(null)->willReturn('json')->shouldBeCalled();

        $request->getMimeType('json')->willReturn('application/json')->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_throws_an_exception_when_format_is_not_accepted(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag(['_format' => 'json-ld']);
        $request->headers = new ParameterBag();

        $request->getRequestFormat(null)->willReturn('json-ld')->shouldBeCalled();

        $request->getMimeType('json-ld')->willReturn('application/json-ld')->shouldBeCalled();

        $this->shouldThrow(new NotAcceptableHttpException(
            'Requested format "application/json-ld" is not supported. Supported MIME types are "text/html", "application/json", "application/xml".',
        ))->during('onKernelRequest', [$event]);
    }
}
