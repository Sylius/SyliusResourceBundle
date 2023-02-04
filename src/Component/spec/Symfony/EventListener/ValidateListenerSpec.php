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
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiator;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Symfony\EventListener\ValidateListener;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Webmozart\Assert\Assert;

final class ValidateListenerSpec extends ObjectBehavior
{
    function let(
        RegistryInterface $resourceRegistry,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        MetadataInterface $metadata,
    ): void {
        $operationInitiator = new HttpOperationInitiator(
            $resourceRegistry->getWrappedObject(),
            $resourceMetadataCollectionFactory->getWrappedObject(),
        );

        $this->beConstructedWith($operationInitiator);

        $resourceRegistry->get('app.dummy')->willReturn($metadata);
        $metadata->getAlias()->willReturn('app.dummy');
        $metadata->getClass('model')->willReturn('App\Dummy');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ValidateListener::class);
    }

    function it_validates_form_data(
        HttpKernelInterface $kernel,
        Request $request,
        FormInterface $form,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);
        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create();

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $form->isSubmitted()->willReturn(true)->shouldBeCalled();
        $form->isValid()->willReturn(true)->shouldBeCalled();
        $form->getData()->willReturn($data)->shouldBeCalled();

        $attributes->set('is_valid', true)->shouldBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->getWrappedObject());
    }

    function it_does_nothing_if_controller_result_is_a_response(
        HttpKernelInterface $kernel,
        Request $request,
        FormInterface $form,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            new Response(),
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);
        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create();

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_if_operation_is_not_a_create_or_update_operation(
        HttpKernelInterface $kernel,
        Request $request,
        FormInterface $form,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);
        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Index();

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_sets_is_valid_to_false_if_method_is_safe(
        HttpKernelInterface $kernel,
        Request $request,
        FormInterface $form,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);
        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create();

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', false)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_if_there_is_no_form(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->isMethodSafe()->willReturn(false);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);
        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create();

        $operations = new Operations();
        $operations->add('app_dummy_create', $operation);

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = (new Resource(alias: 'app.dummy'))->withOperations($operations);

        $resourceMetadataCollectionFactory->create('App\Dummy')->willReturn($resourceMetadataCollection);

        $attributes->set('is_valid', Argument::any())->shouldNotBeCalled();

        $this->onKernelView($event);
    }
}
