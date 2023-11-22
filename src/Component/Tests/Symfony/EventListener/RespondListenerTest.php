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
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\State\ResponderInterface;
use Sylius\Resource\Symfony\EventListener\RespondListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

final class RespondListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private RequestContextInitiatorInterface|ObjectProphecy $contextInitiator;

    private ResponderInterface|ObjectProphecy $responder;

    private RespondListener $respondListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->contextInitiator = $this->prophesize(RequestContextInitiatorInterface::class);
        $this->responder = $this->prophesize(ResponderInterface::class);

        $this->respondListener = new RespondListener(
            $this->operationInitiator->reveal(),
            $this->contextInitiator->reveal(),
            $this->responder->reveal(),
        );
    }

    /** @test */
    public function it_sets_a_response_on_event(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $response = $this->prophesize(Response::class);
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

        $this->responder->respond(['foo' => 'fighters'], $operation, $context)
            ->willReturn($response)
            ->shouldBeCalled()
        ;

        $this->respondListener->onKernelView($event);

        Assert::eq($event->getResponse(), $response->reveal());
    }

    /** @test */
    public function it_does_nothing_when_controller_result_is_a_response(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $response = $this->prophesize(Response::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->reveal(),
        );

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);
        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');

        $this->responder->respond($response, $operation, $context)
            ->willReturn($response)
            ->shouldNotBeCalled()
        ;

        $this->respondListener->onKernelView($event);
    }
}
