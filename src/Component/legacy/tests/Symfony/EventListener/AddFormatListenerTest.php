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

namespace spec\Sylius\Resource\Symfony\EventListener;

use Negotiation\Negotiator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
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
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private AddFormatListener $addFormatListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);

        $this->addFormatListener = new AddFormatListener(
            $this->operationInitiator->reveal(),
            new Negotiator(),
        );
    }

    /** @test */
    public function it_sets_format_from_accept_header(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event->getRequest()->willReturn($request);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();
        $request->headers = new HeaderBag(['Accept' => 'application/json']);

        $request->getFormat('application/json')->willReturn('json')->shouldBeCalled();

        $request->setRequestFormat('json')->shouldBeCalled();

        $this->addFormatListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_sets_format_from_request(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event->getRequest()->willReturn($request);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag(['_format' => 'json']);
        $request->headers = new HeaderBag();

        $request->getRequestFormat(null)->willReturn('json')->shouldBeCalled();

        $request->getMimeType('json')->willReturn('application/json')->shouldBeCalled();

        $this->addFormatListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_throws_an_exception_when_format_is_not_accepted(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);

        $event->getRequest()->willReturn($request);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag(['_format' => 'json-ld']);
        $request->headers = new HeaderBag();

        $request->getRequestFormat(null)->willReturn('json-ld')->shouldBeCalled();

        $request->getMimeType('json-ld')->willReturn('application/json-ld')->shouldBeCalled();

        $this->expectException(NotAcceptableHttpException::class);
        $this->expectExceptionMessage('Requested format "application/json-ld" is not supported. Supported MIME types are "text/html", "application/json", "application/xml".');

        $this->addFormatListener->onKernelRequest($event->reveal());
    }
}
