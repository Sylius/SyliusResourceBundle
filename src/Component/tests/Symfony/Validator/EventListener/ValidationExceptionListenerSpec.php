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

namespace Tests\Sylius\Resource\Symfony\Validator\EventListener;

use PHPUnit\Framework\TestCase;
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

final class ValidationExceptionListenerTest extends TestCase
{
    private ValidationExceptionListener $listener;

    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->listener = new ValidationExceptionListener($this->serializer);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ValidationExceptionListener::class, $this->listener);
    }

    public function testItTransformsValidationExceptionToAResponse(): void
    {
        $kernel = $this->createMock(KernelInterface::class);
        $request = $this->createMock(Request::class);
        $violationList = new ConstraintViolationList();
        $exception = new ValidationException($violationList);

        $event = new ExceptionEvent(
            $kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception,
        );

        $request->method('getRequestFormat')->willReturn('json');
        $request->method('getMimeType')->with('json')->willReturn('application/json');

        $this->serializer->method('serialize')->with($violationList, 'json')->willReturn('serialized_exception');

        $this->listener->onKernelException($event);

        $response = $event->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('serialized_exception', $response->getContent());
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(new ResponseHeaderBag([
            'Content-Type' => 'application/json; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'deny',
        ]), $response->headers);
    }

    public function testItDoesNothingOnOtherExceptions(): void
    {
        $kernel = $this->createMock(KernelInterface::class);
        $request = $this->createMock(Request::class);
        $exception = $this->createMock(\Throwable::class);

        $event = new ExceptionEvent(
            $kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception,
        );

        $this->serializer->expects($this->never())->method('serialize');

        $this->listener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    public function testItThrowsAnExceptionWhenSerializerIsNotAvailable(): void
    {
        $this->listener = new ValidationExceptionListener(null);
        $kernel = $this->createMock(KernelInterface::class);
        $request = $this->createMock(Request::class);

        $violationList = new ConstraintViolationList();
        $exception = new ValidationException($violationList);

        $event = new ExceptionEvent(
            $kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception,
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The Symfony Serializer is not available. Try running "composer require symfony/serializer".');

        $this->listener->onKernelException($event);
    }
}
