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

namespace Sylius\Resource\Tests\Symfony\EventListener;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\State\FactoryInterface;
use Sylius\Resource\Symfony\EventListener\FactoryListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class FactoryListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private RequestContextInitiatorInterface|ObjectProphecy $requestContextInitiator;

    private FactoryInterface|ObjectProphecy $factory;

    private FactoryListener $factoryListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->requestContextInitiator = $this->prophesize(RequestContextInitiatorInterface::class);
        $this->factory = $this->prophesize(FactoryInterface::class);

        $this->factoryListener = new FactoryListener(
            $this->operationInitiator->reveal(),
            $this->requestContextInitiator->reveal(),
            $this->factory->reveal(),
        );
    }

    /** @test */
    public function it_uses_factory_from_operation(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create();

        $event->getRequest()->willReturn($request);
        $request->attributes = $attributes;

        $context = new Context();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);
        $this->requestContextInitiator->initializeContext($request)->willReturn($context);

        $this->factory->create($operation, $context)->willReturn($data)->shouldBeCalled();

        $attributes->set('data', $data)->shouldBeCalled();

        $this->factoryListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_does_nothing_when_operation_is_not_a_create_operation(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Update();

        $event->getRequest()->willReturn($request);
        $request->attributes = $attributes;

        $context = new Context();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);
        $this->requestContextInitiator->initializeContext($request)->willReturn($context);

        $this->factory->create($operation, $context)->willReturn($data)->shouldNotBeCalled();

        $attributes->set('data', $data)->shouldNotBeCalled();

        $this->factoryListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_does_nothing_when_factory_is_disabled(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create(factory: false);

        $event->getRequest()->willReturn($request);
        $request->attributes = $attributes;

        $context = new Context();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);
        $this->requestContextInitiator->initializeContext($request)->willReturn($context);

        $this->factory->create($operation, $context)->willReturn($data)->shouldNotBeCalled();

        $attributes->set('data', $data)->shouldNotBeCalled();

        $this->factoryListener->onKernelRequest($event->reveal());
    }
}
