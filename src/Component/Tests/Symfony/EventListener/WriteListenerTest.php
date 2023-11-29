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

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\EventListener\WriteListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

final class WriteListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private RequestContextInitiatorInterface|ObjectProphecy $contextInitiator;

    private ProcessorInterface|ObjectProphecy $processor;

    private WriteListener $writeListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->contextInitiator = $this->prophesize(RequestContextInitiatorInterface::class);
        $this->processor = $this->prophesize(ProcessorInterface::class);

        $this->writeListener = new WriteListener(
            $this->operationInitiator->reveal(),
            $this->contextInitiator->reveal(),
            $this->processor->reveal(),
        );
    }

    /** @test */
    public function it_writes_data(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->processor->process(['foo' => 'fighters'], $operation, $context)->shouldBeCalled();

        $this->writeListener->onKernelView($event);
    }

    /** @test */
    public function it_replaces_controller_result_on_event(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->willReturn('persisted_result')->shouldBeCalled();

        $this->writeListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), 'persisted_result');
    }

    /** @test */
    public function it_does_not_replace_controller_result_if_it_is_a_response_already(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);
        $response = $this->prophesize(Response::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->willReturn($response)->shouldBeCalled();

        $this->writeListener->onKernelView($event);

        $response->__toString()->willReturn('response_result');

        Assert::eq($event->getResponse(), 'response_result');
    }

    /** @test */
    public function it_removes_controller_result_on_event_with_delete_method(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('DELETE')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->processor->process(['foo' => 'fighters'], $operation, $context)->willReturn('persisted_result')->shouldBeCalled();

        $this->writeListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), null);
    }

    /** @test */
    public function it_does_nothing_when_operation_cannot_be_write(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $operation->canWrite()->willReturn(false)->shouldBeCalled();

        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(false);

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->shouldNotBeCalled();

        $this->writeListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_operation_method_is_safe(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $this->processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->shouldNotBeCalled();

        $this->writeListener->onKernelView($event);
    }
}
