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
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Symfony\EventListener\SerializeListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

final class SerializeListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        SerializerInterface $serializer,
    ): void {
        $this->beConstructedWith($operationInitiator, $serializer);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(SerializeListener::class);
    }

    function it_serializes_data_to_the_requested_format(
        HttpKernelInterface $kernel,
        Request $request,
        \stdClass $data,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
        SerializerInterface $serializer,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(null)->shouldBeCalled();
        $operation->getNormalizationContext()->willReturn([]);

        $serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), 'serialized_data');
    }

    function it_serializes_data_to_the_requested_format_with_normalization_context(
        HttpKernelInterface $kernel,
        Request $request,
        \stdClass $data,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
        SerializerInterface $serializer,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(null)->shouldBeCalled();
        $operation->getNormalizationContext()->willReturn(['groups' => ['dummy:read']]);

        $serializer->serialize($data, 'json', ['groups' => ['dummy:read']])->willReturn('serialized_data')->shouldBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), 'serialized_data');
    }

    function it_does_nothing_when_operation_is_null(
        HttpKernelInterface $kernel,
        Request $request,
        \stdClass $data,
        HttpOperationInitiatorInterface $operationInitiator,
        SerializerInterface $serializer,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operationInitiator->initializeOperation($request)->willReturn(null);

        $request->getRequestFormat()->willReturn('json');

        $serializer->serialize($data, 'json')->willReturn('serialized_data')->shouldNotBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->getWrappedObject());
    }

    function it_does_nothing_when_format_is_html(
        HttpKernelInterface $kernel,
        Request $request,
        \stdClass $data,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
        SerializerInterface $serializer,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('html');

        $serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldNotBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->getWrappedObject());
    }

    function it_throws_an_exception_when_serializer_is_not_available(
        HttpKernelInterface $kernel,
        Request $request,
        \stdClass $data,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
        SerializerInterface $serializer,
    ): void {
        $this->beConstructedWith($operationInitiator, null);

        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json', []);

        $serializer->serialize($data, 'json')->willReturn('serialized_data')->shouldNotBeCalled();

        $this->shouldThrow(new \LogicException('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".'))
            ->during(
                'onKernelView',
                [$event],
            )
        ;
    }

    function it_does_nothing_if_operation_cannot_be_serialized(
        HttpKernelInterface $kernel,
        Request $request,
        \stdClass $data,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
        SerializerInterface $serializer,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(false)->shouldBeCalled();

        $serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldNotBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->getWrappedObject());
    }
}
