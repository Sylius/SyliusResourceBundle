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

namespace spec\Sylius\Component\Resource\Symfony\Request\State;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Symfony\Request\State\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResponderSpec extends ObjectBehavior
{
    function let(ResponderInterface $htmlResponder, ResponderInterface $apiResponder): void
    {
        $this->beConstructedWith($htmlResponder, $apiResponder);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(Responder::class);
    }

    function it_uses_html_responder_on_html_format(
        \stdClass $data,
        Request $request,
        ResponderInterface $htmlResponder,
        HttpOperation $operation,
        Response $response,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));
        $request->getRequestFormat()->willReturn('html');

        $htmlResponder->respond($data, $operation, $context)->willReturn($response)->shouldBeCalled();

        $this->respond($data, $operation, $context);
    }

    function it_uses_api_responder_on_json_format(
        \stdClass $data,
        Request $request,
        ResponderInterface $apiResponder,
        HttpOperation $operation,
        Response $response,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));
        $request->getRequestFormat()->willReturn('json');

        $apiResponder->respond($data, $operation, $context)->willReturn($response)->shouldBeCalled();

        $this->respond($data, $operation, $context);
    }
}
