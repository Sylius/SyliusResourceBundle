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

namespace spec\Sylius\Component\Resource\State;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\State\Responder;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Tests\Dummy\ResponderWithCallable;
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

    function it_calls_respond_method_from_operation_responder_as_string(
        ContainerInterface $locator,
        ResponderInterface $responder,
    ): void {
        $operation = new Create(responder: '\App\Responder');
        $context = new Context();

        $locator->has('\App\Responder')->willReturn(true);
        $locator->get('\App\Responder')->willReturn($responder);

        $responder->respond([], $operation, $context)->shouldBeCalled();

        $this->respond([], $operation, $context);
    }

    function it_calls_process_method_from_operation_processor_as_callable(): void
    {
        $operation = new Create(responder: [ResponderWithCallable::class, 'respond']);
        $context = new Context();

        $this->respond([], $operation, $context)->shouldHaveType(Response::class);
    }

    function it_returns_null_if_operation_has_no_responder(): void
    {
        $operation = new Create();
        $context = new Context();

        $this->respond([], $operation, $context)->shouldReturn(null);
    }
}
