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

namespace Sylius\Component\Resource\Tests\Symfony\EventListener;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Symfony\EventListener\FlashListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class FlashListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private RequestContextInitiatorInterface|ObjectProphecy $requestContextInitiator;

    private FlashHelperInterface|ObjectProphecy $flashHelper;

    private FlashListener $flashListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->requestContextInitiator = $this->prophesize(RequestContextInitiatorInterface::class);
        $this->flashHelper = $this->prophesize(FlashHelperInterface::class);

        $this->flashListener = new FlashListener(
            $this->operationInitiator->reveal(),
            $this->requestContextInitiator->reveal(),
            $this->flashHelper->reveal(),
        );
    }

    /** @test */
    public function it_adds_flash(): void
    {
        $kernel = $this->prophesize(KernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context);

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldBeCalled();

        $this->flashListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_controller_result_is_a_response(): void
    {
        $kernel = $this->prophesize(KernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);
        $response = $this->prophesize(Response::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->reveal(),
        );

        $request->isMethodSafe()->willReturn(false)->shouldNotBeCalled();

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true)->shouldNotBeCalled();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context);

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->flashListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_method_is_safe(): void
    {
        $kernel = $this->prophesize(KernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context);

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->flashListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_validation_has_failed(): void
    {
        $kernel = $this->prophesize(KernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->getBoolean('is_valid', true)->willReturn(false)->shouldBeCalled();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $context = new Context();

        $this->requestContextInitiator->initializeContext($request)->willReturn($context);

        $this->flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->flashListener->onKernelView($event);
    }
}
