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

use PhpSpec\ObjectBehavior;
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

final class FactoryListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FactoryInterface $factory,
    ): void {
        $this->beConstructedWith($operationInitiator, $requestContextInitiator, $factory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FactoryListener::class);
    }

    function it_uses_factory_from_operation(
        RequestEvent $event,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FactoryInterface $factory,
        \stdClass $data,
    ): void {
        $operation = new Create();

        $event->getRequest()->willReturn($request);
        $request->attributes = $attributes;

        $context = new Context();

        $operationInitiator->initializeOperation($request)->willReturn($operation);
        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $factory->create($operation, $context)->willReturn($data)->shouldBeCalled();

        $attributes->set('data', $data)->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_does_nothing_when_operation_is_not_a_create_operation(
        RequestEvent $event,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FactoryInterface $factory,
        \stdClass $data,
    ): void {
        $operation = new Update();

        $event->getRequest()->willReturn($request);
        $request->attributes = $attributes;

        $context = new Context();

        $operationInitiator->initializeOperation($request)->willReturn($operation);
        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $factory->create($operation, $context)->willReturn($data)->shouldNotBeCalled();

        $attributes->set('data', $data)->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    function it_does_nothing_when_factory_is_disabled(
        RequestEvent $event,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $requestContextInitiator,
        FactoryInterface $factory,
        \stdClass $data,
    ): void {
        $operation = new Create(factory: false);

        $event->getRequest()->willReturn($request);
        $request->attributes = $attributes;

        $context = new Context();

        $operationInitiator->initializeOperation($request)->willReturn($operation);
        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $factory->create($operation, $context)->willReturn($data)->shouldNotBeCalled();

        $attributes->set('data', $data)->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }
}
