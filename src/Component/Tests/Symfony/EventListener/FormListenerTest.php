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
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Symfony\EventListener\FormListener;
use Sylius\Resource\Symfony\Form\Factory\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class FormListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private RequestContextInitiatorInterface|ObjectProphecy $contextInitiator;

    private FormFactoryInterface|ObjectProphecy $formFactory;

    private FormListener $formListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->contextInitiator = $this->prophesize(RequestContextInitiatorInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);

        $this->formListener = new FormListener(
            $this->operationInitiator->reveal(),
            $this->contextInitiator->reveal(),
            $this->formFactory->reveal(),
        );
    }

    /** @test */
    public function it_handles_forms(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create(formType: 'App\Type\DummyType');

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldBeCalled();

        $attributes->set('form', $form)->shouldBeCalled();

        $this->formListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_controller_result_is_a_response(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);
        $response = $this->prophesize(Response::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $response->reveal(),
        );

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create(formType: 'App\Type\DummyType');

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_operation_has_no_form_type(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Create(formType: null);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_not_a_create_or_update(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new Show(formType: 'App\Type\DummyType');

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_a_bulk_update(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $attributes->get('_route')->willReturn('app_dummy_show');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $operation = new BulkUpdate(formType: 'App\Type\DummyType');

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context();
        $this->contextInitiator->initializeContext($request)->willReturn($context);

        $this->formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formListener->onKernelView($event);
    }
}
