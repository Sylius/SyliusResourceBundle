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
use Sylius\Component\Resource\Symfony\EventListener\FormListener;
use Sylius\Component\Resource\Symfony\Form\Factory\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class FormListenerSpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        MetadataInterface $metadata,
        FormFactoryInterface $formFactory,
    ): void {
        $operationInitiator = new HttpOperationInitiator(
            $resourceRegistry->getWrappedObject(),
            $resourceMetadataCollectionFactory->getWrappedObject(),
        );

        $this->beConstructedWith($operationInitiator, new RequestContextInitiator(), $formFactory);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getAlias()->willReturn('app.dummy');
        $metadata->getClass('model')->willReturn('App\Dummy');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FormListener::class);
    }

    function it_handles_forms(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        HttpOperation $operation,
        FormFactoryInterface $formFactory,
        FormInterface $form,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation->getWrappedObject());

        $operation->getFormType()->willReturn('App\Type\DummyType');

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $formFactory->create($operation, Argument::type(Context::class), ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldBeCalled();

        $attributes->set('form', $form)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_controller_result_is_a_response(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        HttpOperation $operation,
        FormFactoryInterface $formFactory,
        FormInterface $form,
        Response $response,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->getWrappedObject(),
        );

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation->getWrappedObject());

        $operation->getFormType()->willReturn('App\Type\DummyType');

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $formFactory->create($operation, Argument::type(Context::class), ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_operation_has_no_form_type(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        HttpOperation $operation,
        FormFactoryInterface $formFactory,
        FormInterface $form,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation->getWrappedObject());

        $operation->getFormType()->willReturn(null)->shouldBeCalled();

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $formFactory->create($operation, Argument::type(Context::class), ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->onKernelView($event);
    }
}
