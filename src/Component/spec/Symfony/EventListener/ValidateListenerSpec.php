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

namespace spec\Sylius\Component\Resource\Symfony\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\Metadata\Operations;
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
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

final class ValidateListenerSpec extends ObjectBehavior
{
    function let(
        HttpOperationInitiatorInterface $operationInitiator,
        ValidatorInterface $validator,
    ): void {
        $this->beConstructedWith($operationInitiator, $validator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ValidateListener::class);
    }

    function it_validates_form_data(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        FormInterface $form,
        ParameterBag $attributes,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operation = new Create();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

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
        HttpOperationInitiatorInterface $operationInitiator,
        FormInterface $form,
        ParameterBag $attributes,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            new Response(),
        );

        $operation = new Create();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

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
        $request->getRequestFormat()->willReturn('html');

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
        HttpOperationInitiatorInterface $operationInitiator,
        FormInterface $form,
        ParameterBag $attributes,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $operation = new Create();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', false)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_if_there_is_no_form(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        ParameterBag $attributes,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $operation = new Create();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $attributes->set('is_valid', Argument::any())->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_validates_resource_on_non_html_format(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        ParameterBag $attributes,
        \stdClass $data,
        ValidatorInterface $validator,
        ConstraintViolationListInterface $constraintViolationList,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');

        $operation = new Create();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $validator->validate($data, null, null)->willReturn($constraintViolationList)->shouldBeCalled();

        $constraintViolationList->count()->willReturn(0)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_validates_resource_with_validation_context_on_non_html_format(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        ParameterBag $attributes,
        \stdClass $data,
        ValidatorInterface $validator,
        ConstraintViolationListInterface $constraintViolationList,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');

        $operation = new Create(validationContext: ['groups' => ['sylius']]);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $validator->validate($data, null, ['sylius'])->willReturn($constraintViolationList)->shouldBeCalled();

        $constraintViolationList->count()->willReturn(0)->shouldBeCalled();

        $this->onKernelView($event);
    }

    function it_throws_an_exception_when_validating_resource_on_non_html_format(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        ParameterBag $attributes,
        ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        \stdClass $data,
        ValidatorInterface $validator,
        ConstraintViolationInterface $constraintViolation,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operation = new Create();

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $constraintViolationList = new ConstraintViolationList([$constraintViolation->getWrappedObject()]);

        $validator->validate($data, null, null)->willReturn($constraintViolationList)->shouldBeCalled();

        $this->shouldThrow()->during('onKernelView', [$event]);
    }

    function it_does_nothing_if_operation_cannot_be_validated(
        HttpKernelInterface $kernel,
        Request $request,
        HttpOperationInitiatorInterface $operationInitiator,
        FormInterface $form,
        ParameterBag $attributes,
        \stdClass $data,
    ): void {
        $event = new ViewEvent(
            $kernel->getWrappedObject(),
            $request->getWrappedObject(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->getWrappedObject(),
        );

        $operation = new Create(validate: false);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $this->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->getWrappedObject());
    }
}
