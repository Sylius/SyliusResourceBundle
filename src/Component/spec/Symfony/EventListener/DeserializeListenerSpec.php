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
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\EventListener\DeserializeListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\SerializerInterface;

final class DeserializeListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        SerializerInterface $serializer,
    ): void {
        $this->beConstructedWith($operationInitiator, $serializer);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(DeserializeListener::class);
    }

    function it_deserializes_data(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
        SerializerInterface $serializer,
        \stdClass $data,
    ): void {
        $event->getRequest()->willReturn($request);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();

        $operation->getResource()->willReturn(new Resource(alias: 'app.dummy', class: 'App\Resource'));
        $operation->getDenormalizationContext()->willReturn(null)->shouldBeCalled();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');
        $request->getContent()->willReturn(['food' => 'fighters']);

        $serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    function it_deserializes_data_with_denormalization_context(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
        SerializerInterface $serializer,
        \stdClass $data,
    ): void {
        $event->getRequest()->willReturn($request);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');
        $request->getContent()->willReturn(['food' => 'fighters']);

        $operation->getResource()->willReturn(new Resource(alias: 'app.dummy', class: 'App\Resource'));
        $operation->getDenormalizationContext()->willReturn(['groups' => ['dummy:write']]);

        $serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', ['groups' => ['dummy:write']])->willReturn($data)->shouldBeCalled();

        $this->onKernelRequest($event);
    }
}
