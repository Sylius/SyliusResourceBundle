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
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Component\Resource\Metadata\BulkUpdate;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Show;
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
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
        RegistryInterface $resourceRegistry,
        MetadataInterface $metadata,
        FormFactoryInterface $formFactory,
    ): void {
        $this->beConstructedWith($operationInitiator, $contextInitiator, $formFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FormListener::class);
    }

    function it_handles_forms(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
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
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create(formType: 'App\Type\DummyType');

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $contextInitiator->initializeContext($request)->willReturn($context);

        $formFactory->create($operation, $context, ['foo' => 'fighters'])
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
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
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
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create(formType: 'App\Type\DummyType');

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $contextInitiator->initializeContext($request)->willReturn($context);

        $formFactory->create($operation, $context, ['foo' => 'fighters'])
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
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
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
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create(formType: null);

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $contextInitiator->initializeContext($request)->willReturn($context);

        $formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_operation_is_not_a_create_or_update(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
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
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Show(formType: 'App\Type\DummyType');

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $contextInitiator->initializeContext($request)->willReturn($context);

        $formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->onKernelView($event);
    }

    function it_does_nothing_when_operation_is_a_bulk_update(
        HttpKernelInterface $kernel,
        Request $request,
        ParameterBag $attributes,
        HttpOperationInitiatorInterface $operationInitiator,
        RequestContextInitiatorInterface $contextInitiator,
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
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new BulkUpdate(formType: 'App\Type\DummyType');

        $operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $contextInitiator->initializeContext($request)->willReturn($context);

        $formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->onKernelView($event);
    }
}
