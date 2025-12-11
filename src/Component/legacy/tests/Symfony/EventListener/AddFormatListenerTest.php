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

namespace Sylius\Component\Resource\Tests\Symfony\EventListener;

use Negotiation\Negotiator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Symfony\EventListener\AddFormatListener;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

final class AddFormatListenerTest extends TestCase
{
    private HttpOperationInitiatorInterface|MockObject $operationInitiator;

    private AddFormatListener $addFormatListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->createMock(HttpOperationInitiatorInterface::class);

        $this->addFormatListener = new AddFormatListener(
            $this->operationInitiator,
            new Negotiator(),
        );
    }

    /** @test */
    public function it_sets_format_from_accept_header(): void
    {
        $event = $this->createMock(RequestEvent::class);
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $event->method('getRequest')->willReturn($request);

        $this->operationInitiator->method('initializeOperation')->with($request)->willReturn($operation);

        $request->attributes = new ParameterBag();
        $request->headers = new HeaderBag(['Accept' => 'application/json']);

        $request->expects($this->once())
            ->method('getFormat')
            ->with('application/json')
            ->willReturn('json');

        $request->expects($this->once())
            ->method('setRequestFormat')
            ->with('json');

        $this->addFormatListener->onKernelRequest($event);
    }

    /** @test */
    public function it_sets_format_from_request(): void
    {
        $event = $this->createMock(RequestEvent::class);
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $event->method('getRequest')->willReturn($request);

        $this->operationInitiator->method('initializeOperation')->with($request)->willReturn($operation);

        $request->attributes = new ParameterBag(['_format' => 'json']);
        $request->headers = new HeaderBag();

        $request->expects($this->once())
            ->method('getRequestFormat')
            ->with(null)
            ->willReturn('json');

        $request->expects($this->once())
            ->method('getMimeType')
            ->with('json')
            ->willReturn('application/json');

        $this->addFormatListener->onKernelRequest($event);
    }

    /** @test */
    public function it_throws_an_exception_when_format_is_not_accepted(): void
    {
        $event = $this->createMock(RequestEvent::class);
        $request = $this->createMock(Request::class);
        $operation = $this->createMock(HttpOperation::class);

        $event->method('getRequest')->willReturn($request);

        $this->operationInitiator->method('initializeOperation')->with($request)->willReturn($operation);

        $request->attributes = new ParameterBag(['_format' => 'json-ld']);
        $request->headers = new HeaderBag();

        $request->expects($this->once())
            ->method('getRequestFormat')
            ->with(null)
            ->willReturn('json-ld');

        $request->expects($this->once())
            ->method('getMimeType')
            ->with('json-ld')
            ->willReturn('application/json-ld');

        $this->expectException(NotAcceptableHttpException::class);
        $this->expectExceptionMessage('Requested format "application/json-ld" is not supported. Supported MIME types are "text/html", "application/json", "application/xml".');

        $this->addFormatListener->onKernelRequest($event);
    }
}
