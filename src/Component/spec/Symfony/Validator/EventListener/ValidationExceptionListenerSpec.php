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

namespace spec\Sylius\Resource\Symfony\Validator\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Resource\Symfony\Validator\EventListener\ValidationExceptionListener;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Webmozart\Assert\Assert;

final class ValidationExceptionListenerSpec extends ObjectBehavior
{
    function let(SerializerInterface $serializer): void
    {
        $this->beConstructedWith($serializer);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ValidationExceptionListener::class);
    }

    function it_transforms_validation_exception_to_a_response(
        KernelInterface $kernel,
        Request $request,
        SerializerInterface $serializer,
    ): void {
        $violationList = new ConstraintViolationList();
        $exception = new ValidationException($violationList);

        $event = new ExceptionEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $exception,
        );

        $request->getRequestFormat()->willReturn('json');
        $request->getMimeType('json')->willReturn('application/json');

        $serializer->serialize($violationList, 'json')->willReturn('serialized_exception')->shouldBeCalled();

        $this->onKernelException($event);

        $response = $event->getResponse();

        Assert::isInstanceOf($response, Response::class);
        Assert::eq($response->getContent(), 'serialized_exception');
        Assert::eq($response->getStatusCode(), 422);
        Assert::eq($response->headers, new ResponseHeaderBag([
            'Content-Type' => 'application/json; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'deny',
        ]));
    }

    function it_does_nothing_on_other_exceptions(
        KernelInterface $kernel,
        Request $request,
        SerializerInterface $serializer,
        \Throwable $exception,
    ): void {
        $event = new ExceptionEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $exception->getWrappedObject(),
        );

        $serializer->serialize(Argument::cetera())->shouldNotBeCalled();

        $this->onKernelException($event);

        Assert::null($event->getResponse());
    }

    function it_throws_an_exception_when_serializer_is_not_available(
        KernelInterface $kernel,
        Request $request,
    ): void {
        $this->beConstructedWith(null);

        $violationList = new ConstraintViolationList();
        $exception = new ValidationException($violationList);

        $event = new ExceptionEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $exception,
        );

        $this->shouldThrow(new \LogicException('The Symfony Serializer is not available. Try running "composer require symfony/serializer".'))
            ->during('onKernelException', [$event])
        ;
    }
}
