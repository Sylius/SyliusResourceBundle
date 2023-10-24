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

namespace spec\Sylius\Component\Resource\Symfony\Form\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Symfony\Form\Factory\FormFactory;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class FormFactorySpec extends ObjectBehavior
{
    function let(SymfonyFormFactoryInterface $formFactory): void
    {
        $this->beConstructedWith($formFactory);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FormFactory::class);
    }

    function it_creates_a_form(
        Operation $operation,
        SymfonyFormFactoryInterface $formFactory,
        FormInterface $form,
    ): void {
        $operation->getFormType()->willReturn('App\Form\DummyType');
        $operation->getFormOptions()->willReturn(['foo' => 'fighters']);

        $formFactory->createNamed('', 'App\Form\DummyType', null, ['foo' => 'fighters', 'csrf_protection' => false])
            ->willReturn($form)
            ->shouldBeCalled()
        ;

        $this->create($operation, new Context())->shouldReturn($form);
    }

    function it_creates_a_form_form_html_request(
        Operation $operation,
        SymfonyFormFactoryInterface $formFactory,
        FormInterface $form,
        Request $request,
    ): void {
        $operation->getFormType()->willReturn('App\Form\DummyType');
        $operation->getFormOptions()->willReturn(['foo' => 'fighters']);

        $request->getRequestFormat()->willReturn('html');

        $formFactory->create('App\Form\DummyType', null, ['foo' => 'fighters'])
            ->willReturn($form)
            ->shouldBeCalled()
        ;

        $this->create($operation, new Context(new RequestOption($request->getWrappedObject())))->shouldReturn($form);
    }

    function it_throws_an_exception_when_operation_has_no_form_type(
        Operation $operation,
        SymfonyFormFactoryInterface $formFactory,
        FormInterface $form,
    ): void {
        $operation->getFormType()->willReturn(null);
        $operation->getFormOptions()->willReturn([]);

        $operation->getName()->willReturn('app_dummy_create');

        $this->shouldThrow(new \RuntimeException('Operation "app_dummy_create" has no configured form type.'))
            ->during('create', [$operation, new Context()])
        ;
    }
}
