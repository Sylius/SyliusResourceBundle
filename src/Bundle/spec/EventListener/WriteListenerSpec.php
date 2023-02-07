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

namespace spec\Sylius\Bundle\ResourceBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\EventListener\WriteListener;
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
use Sylius\Component\Resource\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

final class WriteListenerSpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        MetadataInterface $metadata,
        ProcessorInterface $processor,
    ): void {
        $operationInitiator = new HttpOperationInitiator(
            $resourceRegistry->getWrappedObject(),
            $resourceMetadataCollectionFactory->getWrappedObject(),
        );

        $this->beConstructedWith($operationInitiator, new RequestContextInitiator(), $processor);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getAlias()->willReturn('app.dummy');
        $metadata->getClass('model')->willReturn('App\Dummy');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(WriteListener::class);
    }

    function it_writes_data(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProcessorInterface $processor,
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

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_replaces_controller_result_on_event(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProcessorInterface $processor,
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

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->willReturn('persisted_result')->shouldBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), 'persisted_result');
    }

    function it_removes_controller_result_on_event_with_delete_method(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProcessorInterface $processor,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('DELETE')->shouldBeCalled();

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->willReturn('persisted_result')->shouldBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), null);
    }

    function it_does_nothing_when_operation_cannot_be_write(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProcessorInterface $processor,
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

        $operation->canWrite()->willReturn(false)->shouldBeCalled();

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_operation_method_is_a_get(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperation $operation,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        ProcessorInterface $processor,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;
        $request->getMethod()->willReturn('GET')->shouldBeCalled();

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $processor->process(['foo' => 'fighters'], $operation, Argument::type(Context::class))->shouldNotBeCalled();

        $this->onKernelView($event);
    }
}
