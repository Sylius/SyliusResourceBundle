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

namespace spec\Sylius\Component\Resource\Symfony\EventListener;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Symfony\EventListener\FlashListener;
use Sylius\Component\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class FlashListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
    ): void {
        $this->beConstructedWith($operationInitiator, $requestContextInitiator, $flashHelper);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FlashListener::class);
    }

    function it_adds_flash(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_controller_result_is_a_response(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        Response $response,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->getWrappedObject(),
        );

        $request->isMethodSafe()->willReturn(false)->shouldNotBeCalled();

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldNotBeCalled();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_method_is_safe(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_validation_has_failed(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(false)->shouldBeCalled();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->onKernelView($event);
    }
}
