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

namespace spec\Sylius\Component\Resource\Symfony\Request\State;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Symfony\Request\State\Responder;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResponderSpec extends ObjectBehavior
{
    function let(ContainerInterface $locator): void
    {
        $this->beConstructedWith($locator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Responder::class);
    }

    function it_uses_html_responder_on_html_format(
        \stdClass $data,
        Request $request,
        ContainerInterface $locator,
        ResponderInterface $htmlResponder,
        HttpOperation $operation,
        Response $response,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));
        $request->getRequestFormat()->willReturn('html');

        $locator->has('sylius.state_responder.html')->willReturn(true);
        $locator->get('sylius.state_responder.html')->willReturn($htmlResponder);

        $htmlResponder->respond($data, $operation, $context)->willReturn($response)->shouldBeCalled();

        $this->respond($data, $operation, $context);
    }

    function it_uses_api_responder_on_json_format(
        \stdClass $data,
        Request $request,
        ContainerInterface $locator,
        ResponderInterface $apiResponder,
        HttpOperation $operation,
        Response $response,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));
        $request->getRequestFormat()->willReturn('json');

        $locator->has('sylius.state_responder.api')->willReturn(true);
        $locator->get('sylius.state_responder.api')->willReturn($apiResponder);

        $apiResponder->respond($data, $operation, $context)->willReturn($response)->shouldBeCalled();

        $this->respond($data, $operation, $context);
    }

    function it_throw_an_exception_when_html_responder_was_not_found(
        \stdClass $data,
        Request $request,
        ContainerInterface $locator,
        HttpOperation $operation,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));
        $request->getRequestFormat()->willReturn('html');

        $locator->has('sylius.state_responder.html')->willReturn(false);

        $this->shouldThrow(new \LogicException('Responder "sylius.state_responder.html" was not found but it should.'))
            ->during('respond', [$data, $operation, $context])
        ;
    }

    function it_throw_an_exception_when_json_responder_was_not_found(
        \stdClass $data,
        Request $request,
        ContainerInterface $locator,
        HttpOperation $operation,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));
        $request->getRequestFormat()->willReturn('json');

        $locator->has('sylius.state_responder.api')->willReturn(false);

        $this->shouldThrow(new \LogicException('Responder "sylius.state_responder.api" was not found but it should.'))
            ->during('respond', [$data, $operation, $context])
        ;
    }
}
