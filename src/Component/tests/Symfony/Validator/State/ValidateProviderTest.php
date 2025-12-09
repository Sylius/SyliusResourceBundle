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

namespace Sylius\Resource\Tests\Symfony\Validator\State;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
use Sylius\Resource\Symfony\Validator\State\ValidateProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidateProviderTest extends TestCase
{
    private ProviderInterface $decorated;

    private ValidatorInterface $validator;

    private ValidateProvider $validateProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ProviderInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validateProvider = new ValidateProvider($this->decorated, $this->validator);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ValidateProvider::class, $this->validateProvider);
    }

    public function testItReturnsDataWhenResponseIsReturned(): void
    {
        $operation = new Create();
        $context = new Context();
        $response = new Response();

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($response);

        $this->validator->expects($this->never())->method('validate');

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($response, $result);
    }

    public function testItReturnsDataWhenOperationIsNotCreateOrUpdate(): void
    {
        $operation = new Index();
        $context = new Context();
        $data = new \stdClass();

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator->expects($this->never())->method('validate');

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItReturnsDataWhenValidationIsDisabled(): void
    {
        $operation = new Create(validate: false);
        $context = new Context();
        $data = new \stdClass();

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator->expects($this->never())->method('validate');

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItValidatesDataForNonHtmlFormatWithoutViolations(): void
    {
        $operation = new Create();
        $data = new \stdClass();
        $request = new Request();
        $request->setRequestFormat('json');

        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($data, null, null)
            ->willReturn(new ConstraintViolationList());

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItThrowsValidationExceptionForNonHtmlFormatWithViolations(): void
    {
        $operation = new Create();
        $data = new \stdClass();
        $request = new Request();
        $request->setRequestFormat('json');

        $context = new Context(new RequestOption($request));

        $violation = new ConstraintViolation(
            'This value should not be blank.',
            null,
            [],
            $data,
            'name',
            null,
        );
        $violations = new ConstraintViolationList([$violation]);

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($data, null, null)
            ->willReturn($violations);

        $this->expectException(ValidationException::class);

        $this->validateProvider->provide($operation, $context);
    }

    public function testItValidatesDataWithValidationGroups(): void
    {
        $operation = new Create(validationContext: ['groups' => ['Default', 'create']]);
        $data = new \stdClass();
        $request = new Request();
        $request->setRequestFormat('json');

        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($data, null, ['Default', 'create'])
            ->willReturn(new ConstraintViolationList());

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItReturnsDataWhenNoFormExistsForHtmlFormat(): void
    {
        $operation = new Create();
        $data = new \stdClass();
        $request = new Request();
        $request->setRequestFormat('html');

        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator->expects($this->never())->method('validate');

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItValidatesDataWhenNoRequestExists(): void
    {
        $operation = new Create();
        $context = new Context();
        $data = new \stdClass();

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($data, null, null)
            ->willReturn(new ConstraintViolationList());

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItHandlesValidFormForHtmlFormat(): void
    {
        $operation = new Create();
        $formData = new \stdClass();
        $request = Request::create('/test', 'POST');
        $request->setRequestFormat('html');

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('isSubmitted')->willReturn(true);
        $form->expects($this->once())->method('isValid')->willReturn(true);
        $form->expects($this->once())->method('getData')->willReturn($formData);

        $request->attributes->set('form', $form);

        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn(new \stdClass());

        $this->validator->expects($this->never())->method('validate');

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($formData, $result);
        $this->assertTrue($request->attributes->get('is_valid'));
    }

    public function testItHandlesInvalidFormForHtmlFormat(): void
    {
        $operation = new Create();
        $data = new \stdClass();
        $request = Request::create('/test', 'POST');
        $request->setRequestFormat('html');

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('isSubmitted')->willReturn(true);
        $form->expects($this->once())->method('isValid')->willReturn(false);
        $form->expects($this->never())->method('getData');

        $request->attributes->set('form', $form);

        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator->expects($this->never())->method('validate');

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
        $this->assertFalse($request->attributes->get('is_valid'));
    }

    public function testItHandlesSafeMethodForHtmlFormat(): void
    {
        $operation = new Create();
        $data = new \stdClass();
        $request = Request::create('/test', 'GET');
        $request->setRequestFormat('html');

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->never())->method('isSubmitted');
        $form->expects($this->never())->method('isValid');
        $form->expects($this->never())->method('getData');

        $request->attributes->set('form', $form);

        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator->expects($this->never())->method('validate');

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
        $this->assertFalse($request->attributes->get('is_valid'));
    }

    public function testItValidatesUpdateOperation(): void
    {
        $operation = new Update();
        $data = new \stdClass();
        $request = new Request();
        $request->setRequestFormat('json');

        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($data, null, null)
            ->willReturn(new ConstraintViolationList());

        $result = $this->validateProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }
}
