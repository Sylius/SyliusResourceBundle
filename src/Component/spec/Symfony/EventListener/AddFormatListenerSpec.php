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

use Negotiation\Negotiator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\Symfony\EventListener\AddFormatListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class AddFormatListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
    ): void {
        $this->beConstructedWith($operationInitiator, new Negotiator());
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AddFormatListener::class);
    }

    function it_sets_format_from_accept_header(
        RequestEvent $event,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        HttpOperation $operation,
    ): void {
        $event->getRequest()->willReturn($request);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();
        $request->headers = new ParameterBag(['Accept' => 'application/json']);

        $request->getFormat('application/json')->willReturn('json');

        $request->setRequestFormat('json')->shouldBeCalled();

        $this->onKernelRequest($event);
    }
}
