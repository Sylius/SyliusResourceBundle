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

namespace Sylius\Resource\Tests\Symfony\Validator\EventListener;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\Validator\EventListener\ValidationExceptionListener;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

final class ValidationExceptionListenerTest extends TestCase
{
    private SerializerInterface $serializer;

    private ValidationExceptionListener $validationExceptionListener;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validationExceptionListener = new ValidationExceptionListener($this->serializer);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ValidationExceptionListener::class, $this->validationExceptionListener);
    }

    public function testItTransformsValidationExceptionToAResponse(): void
    {
        $violationList = new ConstraintViolationList();
        $exception = new ValidationException($violationList);

        $kernel = $this->createMock(KernelInterface::class);
        $request = $this->createMock(Request::class);

        $event = new ExceptionEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $request->method('getRequestFormat')->willReturn('json');
        $request->method('getMimeType')->with('json')->willReturn('application/json');

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($violationList, 'json')
            ->willReturn('serialized_exception');

        $this->validationExceptionListener->onKernelException($event);

        $response = $event->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('serialized_exception', $response->getContent());
        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame('application/json; charset=utf-8', $response->headers->get('Content-Type'));
        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertSame('deny', $response->headers->get('X-Frame-Options'));
    }

    public function testItDoesNothingOnOtherExceptions(): void
    {
        $exception = new \Exception('Some error');

        $kernel = $this->createMock(KernelInterface::class);
        $request = $this->createMock(Request::class);

        $event = new ExceptionEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->serializer->expects($this->never())->method('serialize');

        $this->validationExceptionListener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    public function testItThrowsAnExceptionWhenSerializerIsNotAvailable(): void
    {
        $validationExceptionListener = new ValidationExceptionListener(null);

        $violationList = new ConstraintViolationList();
        $exception = new ValidationException($violationList);

        $kernel = $this->createMock(KernelInterface::class);
        $request = $this->createMock(Request::class);

        $event = new ExceptionEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The Symfony Serializer is not available. Try running "composer require symfony/serializer".');

        $validationExceptionListener->onKernelException($event);
    }
}
