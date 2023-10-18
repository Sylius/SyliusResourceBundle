<?php

declare(strict_types=1);

namespace Sylius\Component\Resource\Tests\Symfony\EventListener;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\EventListener\DeserializeListener;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\SerializerInterface;

class DeserializeListenerTest extends TestCase
{
    use ProphecyTrait;

    public function testItDeserializeData(): void
    {
        $event = $this->prophesize(RequestEvent::class);
        $request = $this->prophesize(Request::class);
        $operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $operation = $this->prophesize(HttpOperation::class);
        $serializer = $this->prophesize(SerializerInterface::class);
        $data = $this->prophesize(\stdClass::class);

        $event->getRequest()->willReturn($request);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = new ParameterBag();

        $operation->getResource()->willReturn(new Resource(alias: 'app.dummy', class: 'App\Resource'));
        $operation->getDenormalizationContext()->willReturn(null)->shouldBeCalled();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');
        $request->getContent()->willReturn(['food' => 'fighters']);

        $operation->canDeserialize()->willReturn(null)->shouldBeCalled();
        $operation->getDenormalizationContext()->willReturn([])->shouldBeCalled();

        $serializer->deserialize(['food' => 'fighters'], 'App\Resource', 'json', [])->willReturn($data)->shouldBeCalled();

        $deserializeListener = new DeserializeListener($operationInitiator->reveal(), $serializer->reveal());
        $deserializeListener->onKernelRequest($event->reveal());
    }
}
