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

namespace spec\Sylius\Resource\State;

use PhpSpec\ObjectBehavior;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Tests\Dummy\ResponderWithCallable;
use Sylius\Resource\Context\Context;
use Sylius\Resource\State\Responder;
use Sylius\Resource\State\ResponderInterface;
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

    function it_throws_an_exception_when_configured_responder_is_not_a_responder_instance(
        ContainerInterface $locator,
    ): void {
        $operation = new Create(responder: '\stdClass');
        $context = new Context();

        $locator->has('\stdClass')->willReturn(true);
        $locator->get('\stdClass')->willReturn(new \stdClass());

        $this->shouldThrow(new \InvalidArgumentException('Expected an instance of Sylius\Resource\State\ResponderInterface. Got: stdClass'))
            ->during('respond', [[], $operation, $context])
        ;
    }
}
