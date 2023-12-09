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
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Form\Factory\FormFactoryInterface;
use Sylius\Resource\Symfony\Form\State\FormProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FormProviderTest extends TestCase
{
    use ProphecyTrait;

    private ProviderInterface|ObjectProphecy $decorated;

    private FormFactoryInterface|ObjectProphecy $formFactory;

    private FormProvider $formProvider;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ProviderInterface::class);
        $this->formFactory = $this->prophesize(FormFactoryInterface::class);

        $this->formProvider = new FormProvider(
            $this->decorated->reveal(),
            $this->formFactory->reveal(),
        );
    }

    /** @test */
    public function it_handles_forms(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();

        $operation = new Create(formType: 'App\Type\DummyType');

        $context = new Context(new RequestOption($request->reveal()));

        $this->decorated->provide($operation, $context)->willReturn(['foo' => 'fighters'])->shouldBeCalled();

        $this->formFactory->create($operation, $context, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldBeCalled();

        $attributes->set('form', $form)->shouldBeCalled();

        $this->formProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_data_is_a_response(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);
        $response = $this->prophesize(Response::class);

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $operation = new Create(formType: 'App\Type\DummyType');

        $context = new Context(new RequestOption($request->reveal()));
        $this->decorated->provide($operation, $context)->willReturn($response)->shouldBeCalled();

        $this->formFactory->create(Argument::cetera())
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_has_no_form_type(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html')->shouldBeCalled();

        $operation = new Create(formType: null);

        $context = new Context(new RequestOption($request->reveal()));

        $this->formFactory->create(Argument::cetera())
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_not_a_create_or_update(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $operation = new Show(formType: 'App\Type\DummyType');

        $context = new Context(new RequestOption($request->reveal()));

        $this->formFactory->create(Argument::cetera())
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formProvider->provide($operation, $context);
    }

    /** @test */
    public function it_does_nothing_when_operation_is_a_bulk_update(): void
    {
        $request = $this->prophesize(Request::class);
        $attributes = $this->prophesize(ParameterBag::class);
        $form = $this->prophesize(FormInterface::class);

        $request->attributes = $attributes;
        $request->getRequestFormat()->willReturn('html');

        $operation = new BulkUpdate(formType: 'App\Type\DummyType');

        $operations = new Operations();
        $operations->add('app_dummy_show', $operation);

        $context = new Context(new RequestOption($request->reveal()));

        $this->formFactory->create(Argument::cetera())
            ->willReturn($form)
            ->shouldNotBeCalled()
        ;

        $form->handleRequest($request)->willReturn($form)->shouldNotBeCalled();

        $attributes->set('form', $form)->shouldNotBeCalled();

        $this->formProvider->provide($operation, $context);
    }
}
