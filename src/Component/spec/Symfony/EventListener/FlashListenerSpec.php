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
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Symfony\EventListener\FlashListener;
use Sylius\Component\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class FlashListenerSpec extends ObjectBehavior
{
    function let(
        RequestContextInitiatorInterface $requestContextInitiator,
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        MetadataInterface $metadata,
        FlashHelperInterface $flashHelper,
    ): void {
        $operationInitiator = new HttpOperationInitiator(
            $resourceRegistry->getWrappedObject(),
            $resourceMetadataCollectionFactory->getWrappedObject(),
        );

        $this->beConstructedWith($operationInitiator, $requestContextInitiator, $flashHelper);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getAlias()->willReturn('app.dummy');
        $metadata->getClass('model')->willReturn('App\Dummy');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FlashListener::class);
    }

    function it_adds_flash(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);
        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_controller_result_is_a_response(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        Response $response,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->getWrappedObject(),
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);
        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_method_is_safe(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(true);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);
        $attributes->getBoolean('is_valid', true)->willReturn(true);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_validation_has_failed(
        KernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        RequestContextInitiatorInterface $requestContextInitiator,
        FlashHelperInterface $flashHelper,
        HttpOperation $operation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);
        $attributes->getBoolean('is_valid', true)->willReturn(false);

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation->getWrappedObject());

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $context = new Context();

        $requestContextInitiator->initializeContext($request)->willReturn($context);

        $flashHelper->addSuccessFlash($operation, $context)->shouldNotBeCalled();

        $this->onKernelView($event);
    }
}
