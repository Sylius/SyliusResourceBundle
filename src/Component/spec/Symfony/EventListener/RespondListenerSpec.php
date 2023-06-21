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

namespace spec\Sylius\Component\Resource\Symfony\EventListener;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Symfony\EventListener\RespondListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

final class RespondListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ResponderInterface $responder,
    ): void {
        $this->beConstructedWith($operationInitiator, $contextInitiator, $responder);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RespondListener::class);
    }

    function it_sets_a_response_on_event(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResponderInterface $responder,
        Response $response,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $context = new Context();

        $contextInitiator->initializeContext($request)->willReturn($context);
        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');

        $responder->respond(['foo' => 'fighters'], $operation, $context)
            ->willReturn($response)
            ->shouldBeCalled()
        ;

        $this->onKernelView($event);

        Assert::eq($event->getResponse(), $response->getWrappedObject());
    }

    function it_does_nothing_when_controller_result_is_a_response(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResponderInterface $responder,
        Response $response,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->getWrappedObject(),
        );

        $context = new Context();

        $contextInitiator->initializeContext($request)->willReturn($context);
        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');

        $responder->respond($response, $operation, $context)
            ->willReturn($response)
            ->shouldNotBeCalled()
        ;

        $this->onKernelView($event);
    }
}
