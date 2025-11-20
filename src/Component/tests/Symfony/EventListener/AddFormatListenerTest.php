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

namespace Sylius\Resource\Tests\Symfony\EventListener;

use Negotiation\Negotiator;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Symfony\EventListener\AddFormatListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class AddFormatListenerTest extends TestCase
{
    private HttpOperationInitiatorInterface $operationInitiator;

    private Negotiator $negotiator;

    private AddFormatListener $addFormatListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->createMock(HttpOperationInitiatorInterface::class);
        $this->negotiator = new Negotiator();
        $this->addFormatListener = new AddFormatListener($this->operationInitiator, $this->negotiator);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(AddFormatListener::class, $this->addFormatListener);
    }

    public function testItDoesNothingWhenOperationIsNull(): void
    {
        $request = new Request();
        $event = $this->createRequestEvent($request);

        $this->operationInitiator
            ->expects($this->once())
            ->method('initializeOperation')
            ->with($request)
            ->willReturn(null);

        $this->addFormatListener->onKernelRequest($event);
    }

    public function testItSetsFormatFromAcceptHeaderWhenMediaTypeIsNegotiated(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/json');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->addFormatListener->onKernelRequest($event);

        $this->assertSame('json', $request->getRequestFormat());
    }

    public function testItSetsFormatFromAcceptHeaderForXml(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/xml');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->addFormatListener->onKernelRequest($event);

        $this->assertSame('xml', $request->getRequestFormat());
    }

    public function testItSetsFormatFromAcceptHeaderForHtml(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'text/html');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->addFormatListener->onKernelRequest($event);

        $this->assertSame('html', $request->getRequestFormat());
    }

    public function testItDoesNothingWhenAcceptHeaderIsNullAndRequestFormatIsNotSet(): void
    {
        $request = new Request();
        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->addFormatListener->onKernelRequest($event);

        $this->assertNull($request->getRequestFormat(null));
    }

    public function testItDoesNothingWhenMediaTypeCannotBeNegotiatedAndRequestFormatIsSupported(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/pdf');
        $request->setRequestFormat('json');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->addFormatListener->onKernelRequest($event);

        $this->assertSame('json', $request->getRequestFormat());
    }

    public function testItThrowsExceptionWhenMediaTypeCannotBeNegotiatedAndRequestFormatIsUnsupported(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/pdf');
        $request->setRequestFormat('pdf');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->expectException(NotAcceptableHttpException::class);

        $this->addFormatListener->onKernelRequest($event);
    }

    public function testItThrowsExceptionWhenRequestFormatHasNoMimeType(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/unknown');
        $request->setRequestFormat('unknown');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->expectException(NotAcceptableHttpException::class);
        $this->expectExceptionMessage('Requested format "" is not supported. Supported MIME types are "text/html", "application/json", "application/xml".');

        $this->addFormatListener->onKernelRequest($event);
    }

    public function testItHandlesComplexAcceptHeaderWithMultipleTypes(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->addFormatListener->onKernelRequest($event);

        $this->assertSame('html', $request->getRequestFormat());
    }

    public function testItDoesNotOverrideFormatWhenRequestFormatIsHtml(): void
    {
        $request = new Request();
        $request->headers->set('Accept', 'application/pdf');
        $request->setRequestFormat('html');

        $event = $this->createRequestEvent($request);
        $operation = $this->createMock(HttpOperation::class);

        $this->operationInitiator
            ->method('initializeOperation')
            ->with($request)
            ->willReturn($operation);

        $this->addFormatListener->onKernelRequest($event);

        $this->assertSame('html', $request->getRequestFormat());
    }

    private function createRequestEvent(Request $request): RequestEvent
    {
        $kernel = $this->createMock(KernelInterface::class);

        return new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);
    }
}
