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
use Prophecy\Argument;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\State\ProcessorInterface;
use Sylius\Component\Resource\Symfony\EventListener\WriteListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

final class WriteListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ProcessorInterface $processor,
    ): void {
        $this->beConstructedWith($operationInitiator, $contextInitiator, $processor);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(WriteListener::class);
    }

    function it_writes_data(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ProcessorInterface $processor,
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
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $processor->process(['foo' => 'fighters'], $operation, $context)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_replaces_controller_result_on_event(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ProcessorInterface $processor,
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
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->willReturn('persisted_result')->shouldBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), 'persisted_result');
    }

    function it_does_not_replace_controller_result_if_it_is_a_response_already(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ProcessorInterface $processor,
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
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->willReturn($response)->shouldBeCalled();

        $this->onKernelView($event);

        $response->__toString()->willReturn('response_result');

        Assert::eq($event->getResponse(), 'response_result');
    }

    function it_removes_controller_result_on_event_with_delete_method(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ProcessorInterface $processor,
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
        $request->getMethod()->willReturn('DELETE')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $processor->process(['foo' => 'fighters'], $operation, $context)->willReturn('persisted_result')->shouldBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), null);
    }

    function it_does_nothing_when_operation_cannot_be_write(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ProcessorInterface $processor,
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

        $operation->canWrite()->willReturn(false)->shouldBeCalled();

        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_operation_method_is_safe(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ProcessorInterface $processor,
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

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->shouldNotBeCalled();

        $this->onKernelView($event);
    }
}
