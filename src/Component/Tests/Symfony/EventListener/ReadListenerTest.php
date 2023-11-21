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
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\EventListener\ReadListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private RequestContextInitiatorInterface|ObjectProphecy $contextInitiator;

    private ProviderInterface|ObjectProphecy $provider;

    private ReadListener $readListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->contextInitiator = $this->prophesize(RequestContextInitiatorInterface::class);
        $this->provider = $this->prophesize(ProviderInterface::class);

        $this->readListener = new ReadListener(
            $this->operationInitiator->reveal(),
            $this->contextInitiator->reveal(),
            $this->provider->reveal(),
        );
    }

    /** @test */
    public function it_retrieves_data_and_store_them_to_request(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event->getRequest()->willReturn($request);

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $this->provider->provide($operation, Argument::type(Context::class))->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $attributes->set('data', ['foo' => 'fighters'])->shouldBeCalled();

        $this->readListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_does_nothing_when_operation_is_a_create_operation(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);

        $event->getRequest()->willReturn($request);

        $operation = new Create();

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $this->provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->readListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_does_nothing_when_operation_cannot_be_read(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event->getRequest()->willReturn($request);

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation->canRead()->willReturn(false);

        $this->provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->readListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_throws_an_exception_when_no_data_was_found(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event->getRequest()->willReturn($request);

        $context = new Context();

        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $operation->canRead()->willReturn(true);

        $this->provider->provide($operation, Argument::type(Context::class))->willReturn(null);

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Resource has not been found.');

        $this->readListener->onKernelRequest($event->reveal());
    }
}
