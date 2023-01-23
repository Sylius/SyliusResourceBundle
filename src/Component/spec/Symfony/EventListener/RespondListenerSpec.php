<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Resource\Symfony\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiator;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Symfony\EventListener\RespondListener;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

final class RespondListenerSpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        MetadataInterface $metadata,
        ResponderInterface $responder,
    ): void {
        $operationInitiator = new HttpOperationInitiator(
            $resourceRegistry->getWrappedObject(),
            $resourceMetadataCollectionFactory->getWrappedObject(),
        );

        $this->beConstructedWith($operationInitiator, new RequestContextInitiator(), $responder);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getAlias()->willReturn('app.dummy');
        $metadata->getClass('model')->willReturn('App\Dummy');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RespondListener::class);
    }

    function it_sets_a_response_on_event(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ResponderInterface $responder,
        Response $response,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $responder->respond(['foo' => 'fighters'], $operation, Argument::type(Context::class))
            ->willReturn($response)
            ->shouldBeCalled()
        ;

        $this->onKernelView($event);

        Assert::eq($event->getResponse(), $response->getWrappedObject());
    }

    function it_does_nothing_when_controller_result_is_a_response(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ResponderInterface $responder,
        Response $response,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->getWrappedObject(),
        );

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('POST');

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $responder->respond($response, $operation, Argument::type(Context::class))
            ->willReturn($response)
            ->shouldNotBeCalled()
        ;

        $this->onKernelView($event);
    }
}
