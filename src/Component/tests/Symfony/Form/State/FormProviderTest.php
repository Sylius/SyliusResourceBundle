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

namespace Sylius\Component\Resource\tests\Symfony\Form\State;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Form\Factory\FormFactoryInterface;
use Sylius\Resource\Symfony\Form\State\FormProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FormProviderTest extends TestCase
{
    private ProviderInterface $decorated;

    private FormFactoryInterface $formFactory;

    private FormProvider $formProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(ProviderInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);

        $this->formProvider = new FormProvider(
            $this->decorated,
            $this->formFactory,
        );
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(FormProvider::class, $this->formProvider);
    }

    public function testItHandlesFormsForCreateOperation(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $form = $this->createMock(FormInterface::class);
        $data = ['foo' => 'fighters'];

        $request->attributes = $attributes;

        $request
            ->expects($this->once())
            ->method('getRequestFormat')
            ->willReturn('html');

        $operation = new Create(formType: 'App\Type\DummyType');
        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with($operation, $context, $data)
            ->willReturn($form);

        $form
            ->expects($this->once())
            ->method('handleRequest')
            ->with($request)
            ->willReturn($form);

        $attributes
            ->expects($this->once())
            ->method('set')
            ->with('form', $form);

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItHandlesFormsForUpdateOperation(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $form = $this->createMock(FormInterface::class);
        $data = new \stdClass();

        $request->attributes = $attributes;

        $request
            ->expects($this->once())
            ->method('getRequestFormat')
            ->willReturn('html');

        $operation = new Update(formType: 'App\Type\DummyType');
        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with($operation, $context, $data)
            ->willReturn($form);

        $form
            ->expects($this->once())
            ->method('handleRequest')
            ->with($request)
            ->willReturn($form);

        $attributes
            ->expects($this->once())
            ->method('set')
            ->with('form', $form);

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItDoesNothingWhenDataIsAResponse(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $response = $this->createMock(Response::class);

        $request->attributes = $attributes;

        $request
            ->expects($this->once())
            ->method('getRequestFormat')
            ->willReturn('html');

        $operation = new Create(formType: 'App\Type\DummyType');
        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($response);

        $this->formFactory
            ->expects($this->never())
            ->method('create');

        $attributes
            ->expects($this->never())
            ->method('set');

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($response, $result);
    }

    public function testItDoesNothingWhenOperationHasNoFormType(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = ['foo' => 'bar'];

        $request->attributes = $attributes;

        $request
            ->expects($this->once())
            ->method('getRequestFormat')
            ->willReturn('html');

        $operation = new Create(formType: null);
        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->formFactory
            ->expects($this->never())
            ->method('create');

        $attributes
            ->expects($this->never())
            ->method('set');

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItDoesNothingWhenOperationIsNotACreateOrUpdate(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = ['foo' => 'bar'];

        $request->attributes = $attributes;

        $request
            ->expects($this->once())
            ->method('getRequestFormat')
            ->willReturn('html');

        $operation = new Show(formType: 'App\Type\DummyType');
        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->formFactory
            ->expects($this->never())
            ->method('create');

        $attributes
            ->expects($this->never())
            ->method('set');

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItDoesNothingWhenOperationIsABulkUpdate(): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = ['foo' => 'bar'];

        $request->attributes = $attributes;

        $request
            ->expects($this->once())
            ->method('getRequestFormat')
            ->willReturn('html');

        $operation = new BulkUpdate(formType: 'App\Type\DummyType');
        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->formFactory
            ->expects($this->never())
            ->method('create');

        $attributes
            ->expects($this->never())
            ->method('set');

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    public function testItReturnsDataWhenRequestIsNull(): void
    {
        $data = ['foo' => 'bar'];
        $operation = new Create(formType: 'App\Type\DummyType');
        $context = new Context();

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->formFactory
            ->expects($this->never())
            ->method('create');

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    /**
     * @dataProvider nonHtmlRequestFormatProvider
     */
    public function testItDoesNothingWhenRequestFormatIsNotHtml(string $requestFormat): void
    {
        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $data = ['foo' => 'bar'];

        $request->attributes = $attributes;

        $request
            ->expects($this->once())
            ->method('getRequestFormat')
            ->willReturn($requestFormat);

        $operation = new Create(formType: 'App\Type\DummyType');
        $context = new Context(new RequestOption($request));

        $this->decorated
            ->expects($this->once())
            ->method('provide')
            ->with($operation, $context)
            ->willReturn($data);

        $this->formFactory
            ->expects($this->never())
            ->method('create');

        $attributes
            ->expects($this->never())
            ->method('set');

        $result = $this->formProvider->provide($operation, $context);

        $this->assertSame($data, $result);
    }

    /**
     * @return iterable<string, array<string>>
     */
    public static function nonHtmlRequestFormatProvider(): iterable
    {
        yield 'json' => ['json'];
        yield 'xml' => ['xml'];
    }
}
