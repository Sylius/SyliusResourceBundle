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

namespace Sylius\Component\Resource\Tests\Symfony\Validator\State;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
use Sylius\Resource\Symfony\Validator\State\ValidateProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Webmozart\Assert\Assert;

final class ValidateProviderTest extends TestCase
{
    use ProphecyTrait;

    private ProviderInterface|ObjectProphecy $decorated;

    private ValidatorInterface|ObjectProphecy $validator;

    private ValidateProvider $validateProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProviderInterface::class);
        $this->validator = $this->prophesize(ValidatorInterface::class);

        $this->validateProvider = new ValidateProvider(
            $this->decorated->reveal(),
            $this->validator->reveal(),
        );
    }

    /** @test */
    public function it_validates_form_data(): void
    {
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldBeCalled();
        $form->isValid()->willReturn(true)->shouldBeCalled();
        $form->getData()->willReturn($data)->shouldBeCalled();

        $attributes->set('is_valid', true)->shouldBeCalled();

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_controller_result_is_a_response(): void
    {
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn(new Response())->shouldBeCalled();

        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false)->shouldNotBeCalled();

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_is_not_a_create_or_update_operation(): void
    {
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Index();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

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

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_sets_is_valid_to_false_if_method_is_safe(): void
    {
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->isMethodSafe()->willReturn(true)->shouldBeCalled();
        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form);

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', false)->shouldBeCalled();

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_there_is_no_form(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $request->isMethodSafe()->willReturn(false);
        $request->getRequestFormat()->willReturn('html');

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $attributes->set('is_valid', Argument::any())->shouldNotBeCalled();

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_validates_resource_on_non_html_format(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);
        $constraintViolationList = $this->prophesize(ConstraintViolationListInterface::class);

        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn($data)->shouldBeCalled();

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $this->validator->validate($data, null, null)->willReturn($constraintViolationList)->shouldBeCalled();

        $constraintViolationList->count()->willReturn(0)->shouldBeCalled();

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_validates_resource_with_validation_context_on_non_html_format(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);
        $constraintViolationList = $this->prophesize(ConstraintViolationListInterface::class);

        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $operation = new Create(validationContext: ['groups' => ['sylius']]);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn($data)->shouldBeCalled();

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null);

        $this->validator->validate($data, null, ['sylius'])->willReturn($constraintViolationList)->shouldBeCalled();

        $constraintViolationList->count()->willReturn(0)->shouldBeCalled();

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_throws_an_exception_when_validating_resource_on_non_html_format(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);
        $constraintViolation = $this->prophesize(ConstraintViolationInterface::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn($data)->shouldBeCalled();

        $request->getRequestFormat()->willReturn('json')->shouldBeCalled();

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn(null)->shouldBeCalled();

        $constraintViolation->getPropertyPath()->willReturn('property');
        $constraintViolation->getMessage()->willReturn('Error message');
        $constraintViolationList = new ConstraintViolationList([$constraintViolation->reveal()]);

        $this->validator->validate($data, null, null)->willReturn($constraintViolationList)->shouldBeCalled();

        $this->expectException(ValidationException::class);

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_cannot_be_validated(): void
    {
        $request = $this->prophesize(Request::class);
        $form = $this->prophesize(FormInterface::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $data = $this->prophesize(\stdClass::class);

        $operation = new Create(validate: false);

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn($data)->shouldBeCalled();

        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();
        $request->isMethodSafe()->willReturn(false)->shouldNotBeCalled();

        $request->attributes = $attributes;

        $attributes->get('form')->willReturn($form)->shouldBeCalled();

        $form->isSubmitted()->willReturn(true)->shouldNotBeCalled();
        $form->isValid()->willReturn(true)->shouldNotBeCalled();
        $form->getData()->willReturn($data)->shouldNotBeCalled();

        $attributes->set('is_valid', true)->shouldNotBeCalled();

        $result = $this->validateProvider->provide($operation, $context);

        Assert::eq($result, $data->reveal());
    }
}
