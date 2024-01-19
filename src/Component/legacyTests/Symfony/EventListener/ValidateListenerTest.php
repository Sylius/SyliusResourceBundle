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
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operation\HttpOperationInitiatorInterface;
use Sylius\Resource\Symfony\EventListener\ValidateListener;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
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

final class ValidateListenerTest extends TestCase
{
    use ProphecyTrait;

    private HttpOperationInitiatorInterface|ObjectProphecy $operationInitiator;

    private ValidatorInterface|ObjectProphecy $validator;

    private ValidateListener $validateListener;

    protected function setUp(): void
    {
        $this->operationInitiator = $this->prophesize(HttpOperationInitiatorInterface::class);
        $this->validator = $this->prophesize(ValidatorInterface::class);

        $this->validateListener = new ValidateListener(
            $this->operationInitiator->reveal(),
            $this->validator->reveal(),
        );
    }

    /** @test */
    public function it_validates_form_data(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $operation = new Create();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldBeCalled();
        $form->isValid()->willReturn(true)->shouldBeCalled();
        $form->getData()->willReturn($data)->shouldBeCalled();

        $attributes->set('is_valid', true)->shouldBeCalled();

        $this->validateListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->reveal());
    }

    /** @test */
    public function it_does_nothing_if_controller_result_is_a_response(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            new Response(),
        );

        $operation = new Create();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $this->validateListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_if_operation_is_not_a_create_or_update_operation(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $operation = new Index();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);
        $attributes->get('_route')->willReturn('app_dummy_create');
        $attributes->all('_sylius')->willReturn(['resource' => 'app.dummy']);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $this->validateListener->onKernelView($event);
    }

    /** @test */
    public function it_sets_is_valid_to_false_if_method_is_safe(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            ['foo' => 'fighters'],
        );

        $operation = new Create();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', false)->shouldBeCalled();

        $this->validateListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_if_there_is_no_form(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $operation = new Create();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $attributes->set('is_valid', Argument::any())->shouldNotBeCalled();

        $this->validateListener->onKernelView($event);
    }

    /** @test */
    public function it_validates_resource_on_non_html_format(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);
        $constraintViolationList = $this->prophesize(ConstraintViolationListInterface::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $operation = new Create();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $this->validator->validate($data, null, null)->willReturn($constraintViolationList)->shouldBeCalled();

        $constraintViolationList->count()->willReturn(0)->shouldBeCalled();

        $this->validateListener->onKernelView($event);
    }

    /** @test */
    public function it_validates_resource_with_validation_context_on_non_html_format(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);
        $constraintViolationList = $this->prophesize(ConstraintViolationListInterface::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $operation = new Create(validationContext: ['groups' => ['sylius']]);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $this->validator->validate($data, null, ['sylius'])->willReturn($constraintViolationList)->shouldBeCalled();

        $constraintViolationList->count()->willReturn(0)->shouldBeCalled();

        $this->validateListener->onKernelView($event);
    }

    /** @test */
    public function it_throws_an_exception_when_validating_resource_on_non_html_format(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);
        $constraintViolation = $this->prophesize(ConstraintViolationInterface::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $operation = new Create();

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('json');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $constraintViolation->getPropertyPath()->willReturn('property');
        $constraintViolation->getMessage()->willReturn('Error message');
        $constraintViolationList = new ConstraintViolationList([$constraintViolation->reveal()]);

        $this->validator->validate($data, null, null)->willReturn($constraintViolationList)->shouldBeCalled();

        $this->expectException(ValidationException::class);

        $this->validateListener->onKernelView($event);
    }

    /** @test */
    public function it_does_nothing_if_operation_cannot_be_validated(): void
    {
        $kernel = $this->prophesize(HttpKernelInterface::class);
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $event = new ViewEvent(
            $kernel->reveal(),
            $request->reveal(),
            HttpKernelInterface::MAIN_REQUEST,
            $data->reveal(),
        );

        $operation = new Create(validate: false);

        $this->operationInitiator->initializeOperation($request)->willReturn($operation);

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $this->validateListener->onKernelView($event);

        Assert::eq($event->getControllerResult(), $data->reveal());
    }
}
