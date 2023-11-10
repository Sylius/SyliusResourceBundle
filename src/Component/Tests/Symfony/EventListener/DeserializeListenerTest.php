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
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\EventListener\DeserializeListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\SerializerInterface;

final class DeserializeListenerTest extends TestCase
{
    use ProphecyTrait;

    /** @var HttpOperationInitiatorInterface|ObjectProphecy */
    private $operationInitiator;

    /** @var SerializerInterface|ObjectProphecy */
    private $serializer;

    /** @var DeserializeListener */
    private $deserializableListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);

        $this->deserializableListener = new DeserializeListener(
            $this->operationInitiator->reveal(),
            $this->serializer->reveal(),
        );
    }

    /** @test */
    public function it_deserializes_data(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $event->getRequest()->willReturn($request);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->getDenormalizationContext()->willReturn(null)->shouldBeCalled();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');
        $request->getContent()->willReturn(['food' => 'fighters']);

        $operation->canDeserialize()->willReturn(null)->shouldBeCalled();
        $operation->getDenormalizationContext()->willReturn([])->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldBeCalled();

        $this->deserializableListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_deserializes_data_with_denormalization_context(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $event->getRequest()->willReturn($request);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');
        $request->getContent()->willReturn(['food' => 'fighters']);

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->canDeserialize()->willReturn(null)->shouldBeCalled();
        $operation->getDenormalizationContext()->willReturn(['groups' => ['dummy:write']]);

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', ['groups' => ['dummy:write']])->willReturn($data)->shouldBeCalled();

        $this->deserializableListener->onKernelRequest($event->reveal());
    }

    /** @test */
    public function it_does_nothing_if_operation_cannot_be_deserialized(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $operation = $this->prophesize(HttpOperation::class);
        $data = $this->prophesize(\stdClass::class);

        $event->getRequest()->willReturn($request);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');
        $request->getContent()->willReturn(['food' => 'fighters']);

        $operation->getResource()->willReturn(new ResourceMetadata(alias: 'app.dummy', class: 'App\Resource'));
        $operation->canDeserialize()->willReturn(false)->shouldBeCalled();

        $this->serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldNotBeCalled();

        $this->deserializableListener->onKernelRequest($event->reveal());
    }
}
