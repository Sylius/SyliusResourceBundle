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
use Prophecy\Argument;
use Sylius\Component\Resource\Symfony\EventListener\ReadListener;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ProviderInterface $provider,
    ): void {
        $this->beConstructedWith($operationInitiator, $contextInitiator, $provider);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ReadListener::class);
    }

    function it_retrieves_data_and_store_them_to_request(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        HttpOperation $operation,
        ProviderInterface $provider,
    ): void {
        $event->getRequest()->willReturn($request);

        $context = new Context();

        $contextInitiator->initializeContext($request)->willReturn($context);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $provider->provide($operation, Argument::type(Context::class))->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $attributes->set('data', ['foo' => 'fighters'])->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_does_nothing_when_operation_is_a_create_operation(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        ProviderInterface $provider,
    ): void {
        $event->getRequest()->willReturn($request);

        $operation = new Create();

        $context = new Context();

        $contextInitiator->initializeContext($request)->willReturn($context);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    function it_does_nothing_when_operation_cannot_be_read(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        ProviderInterface $provider,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $context = new Context();

        $contextInitiator->initializeContext($request)->willReturn($context);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation->canRead()->willReturn(false);

        $provider->provide($operation, Argument::type(Context::class))->shouldNotBeCalled();

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    function it_throws_an_exception_when_no_data_was_found(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProviderInterface $provider,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $context = new Context();

        $contextInitiator->initializeContext($request)->willReturn($context);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $operation->canRead()->willReturn(true);

        $provider->provide($operation, Argument::type(Context::class))->willReturn(null);

        $attributes->set('data', Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(new NotFoundHttpException('Resource has not been found.'))
            ->during('onKernelRequest', [$event])
        ;
    }
}
