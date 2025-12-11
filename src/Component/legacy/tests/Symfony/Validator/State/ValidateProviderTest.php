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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
    private ProviderInterface|MockObject $decorated;

    private ValidatorInterface|MockObject $validator;

    private ValidateProvider $validateProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ProviderInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->validateProvider = new ValidateProvider(
            $this->decorated,
            $this->validator,
        );
    }

    /** @test */
    public function it_validates_form_data(): void
    {
        $request = $this->createMock(Request::class);
        $form = $this->createMock(FormInterface::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $request->method('isMethodSafe')->willReturn(false);
        $request->method('getRequestFormat')->willReturn('html');

        $request->attributes = $attributes;

        $attributes->method('get')->with('form')->willReturn($form);

        $form->expects($this->once())->method('isSubmitted')->willReturn(true);
        $form->expects($this->once())->method('isValid')->willReturn(true);
        $form->expects($this->once())->method('getData')->willReturn($data);

        $attributes->expects($this->once())
            ->method('set')
            ->with('is_valid', true)
        ;

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_controller_result_is_a_response(): void
    {
        $request = $this->createMock(Request::class);
        $form = $this->createMock(FormInterface::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(new Response())
        ;

        $request->expects($this->once())->method('getRequestFormat')->willReturn('html');
        $request->expects($this->never())->method('isMethodSafe');

        $request->attributes = $attributes;

        $attributes->method('get')->with('form')->willReturn($form);

        $form->expects($this->never())->method('isSubmitted');
        $form->expects($this->never())->method('isValid');
        $form->expects($this->never())->method('getData');

        $attributes->expects($this->never())->method('set')->with('is_valid', true);

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_is_not_a_create_or_update_operation(): void
    {
        $request = $this->createMock(Request::class);
        $form = $this->createMock(FormInterface::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Index();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $request->method('isMethodSafe')->willReturn(false);
        $request->method('getRequestFormat')->willReturn('html');

        $request->attributes = $attributes;

        $attributes->method('get')->willReturnCallback(function (string $key) use ($form) {
            if ($key === 'form') {
                return $form;
            }
            if ($key === '_route') {
                return 'app_dummy_create';
            }

            return null;
        });
        $attributes->method('all')->with('_sylius')->willReturn(['resource' => 'app.dummy']);

        $form->expects($this->never())->method('isSubmitted');
        $form->expects($this->never())->method('isValid');
        $form->expects($this->never())->method('getData');

        $attributes->expects($this->never())->method('set')->with('is_valid', true);

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_sets_is_valid_to_false_if_method_is_safe(): void
    {
        $request = $this->createMock(Request::class);
        $form = $this->createMock(FormInterface::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $request->expects($this->once())->method('isMethodSafe')->willReturn(true);
        $request->expects($this->once())->method('getRequestFormat')->willReturn('html');

        $request->attributes = $attributes;

        $attributes->method('get')->with('form')->willReturn($form);

        $form->expects($this->never())->method('isSubmitted');
        $form->expects($this->never())->method('isValid');
        $form->expects($this->never())->method('getData');

        $attributes->expects($this->once())
            ->method('set')
            ->with('is_valid', false)
        ;

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_there_is_no_form(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(['foo' => 'fighters'])
        ;

        $request->method('isMethodSafe')->willReturn(false);
        $request->method('getRequestFormat')->willReturn('html');

        $request->attributes = $attributes;

        $attributes->method('get')->with('form')->willReturn(null);

        $attributes->expects($this->never())->method('set')->with('is_valid', $this->anything());

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_validates_resource_on_non_html_format(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);
        $constraintViolationList = $this->createMock(ConstraintViolationListInterface::class);

        $request->expects($this->once())->method('getRequestFormat')->willReturn('json');

        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data)
        ;

        $request->attributes = $attributes;

        $attributes->method('get')->with('form')->willReturn(null);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($data, null, null)
            ->willReturn($constraintViolationList)
        ;

        $constraintViolationList->expects($this->once())->method('count')->willReturn(0);

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_validates_resource_with_validation_context_on_non_html_format(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);
        $constraintViolationList = $this->createMock(ConstraintViolationListInterface::class);

        $request->expects($this->once())->method('getRequestFormat')->willReturn('json');

        $operation = new Create(validationContext: ['groups' => ['sylius']]);

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data)
        ;

        $request->attributes = $attributes;

        $attributes->method('get')->with('form')->willReturn(null);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($data, null, ['sylius'])
            ->willReturn($constraintViolationList)
        ;

        $constraintViolationList->expects($this->once())->method('count')->willReturn(0);

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_throws_an_exception_when_validating_resource_on_non_html_format(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);
        $constraintViolation = $this->createMock(ConstraintViolationInterface::class);

        $operation = new Create();

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data)
        ;

        $request->expects($this->once())->method('getRequestFormat')->willReturn('json');

        $request->attributes = $attributes;

        $attributes->expects($this->once())->method('get')->with('form')->willReturn(null);

        $constraintViolation->method('getPropertyPath')->willReturn('property');
        $constraintViolation->method('getMessage')->willReturn('Error message');
        $constraintViolationList = new ConstraintViolationList([$constraintViolation]);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($data, null, null)
            ->willReturn($constraintViolationList)
        ;

        $this->expectException(ValidationException::class);

        $this->validateProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_if_operation_cannot_be_validated(): void
    {
        $request = $this->createMock(Request::class);
        $form = $this->createMock(FormInterface::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = $this->createMock(\stdClass::class);

        $operation = new Create(validate: false);

        $context = new Context(new RequestOption($request));

        $this->decorated->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data)
        ;

        $request->expects($this->once())->method('getRequestFormat')->willReturn('html');
        $request->expects($this->never())->method('isMethodSafe');

        $request->attributes = $attributes;

        $attributes->expects($this->once())->method('get')->with('form')->willReturn($form);

        $form->expects($this->never())->method('isSubmitted');
        $form->expects($this->never())->method('isValid');
        $form->expects($this->never())->method('getData');

        $attributes->expects($this->never())->method('set')->with('is_valid', true);

        $result = $this->validateProvider->provide($operation, $context);

        Assert::eq($result, $data);
    }
}
