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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Symfony\EventListener\SerializeListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

final class SerializeListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private SerializerInterface|ObjectProphecy $serializer;

    private SerializeListener $serializeListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->serializeListener = new SerializeListener(
            $this->operationInitiator->reveal(),
            $this->serializer->reveal(),
        );
    }

    /** @test */
    public function it_serializes_data_to_the_requested_format(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(null)->shouldBeCalled();
        $operation->getNormalizationContext()->willReturn([]);

        $this->serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldBeCalled();

        $this->serializeListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), 'serialized_data');
    }

    /** @test */
    public function it_serializes_data_to_the_requested_format_with_normalization_context(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(null)->shouldBeCalled();
        $operation->getNormalizationContext()->willReturn(['groups' => ['dummy:read']]);

        $this->serializer->serialize($data, 'json', ['groups' => ['dummy:read']])->willReturn('serialized_data')->shouldBeCalled();

        $this->serializeListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), 'serialized_data');
    }

    /** @test */
    public function it_does_nothing_when_operation_is_null(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $this->operationInitiator->initializeOperation($request)->willReturn(null);

        $request->getRequestFormat()->willReturn('json');

        $this->serializer->serialize($data, 'json')->willReturn('serialized_data')->shouldNotBeCalled();

        $this->serializeListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->reveal());
    }

    /** @test */
    public function it_does_nothing_when_format_is_html(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('html');

        $this->serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldNotBeCalled();

        $this->serializeListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->reveal());
    }

    /** @test */
    public function it_throws_an_exception_when_serializer_is_not_available(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $serializeListener = new SerializeListener($this->operationInitiator->reveal(), null);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json', []);

        $this->serializer->serialize($data, 'json')->willReturn('serialized_data')->shouldNotBeCalled();

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('You can not use the "json" format if the Serializer is not available. Try running "composer require symfony/serializer".');

        $serializeListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_if_operation_cannot_be_serialized(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->getRequestFormat()->willReturn('json');

        $operation->canSerialize()->willReturn(false)->shouldBeCalled();

        $this->serializer->serialize($data, 'json', [])->willReturn('serialized_data')->shouldNotBeCalled();

        $this->serializeListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->reveal());
    }
}
