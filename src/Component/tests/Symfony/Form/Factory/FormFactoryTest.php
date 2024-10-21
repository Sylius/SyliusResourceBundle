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

namespace Sylius\Resource\Tests\Symfony\Form\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Symfony\Form\Factory\FormFactory;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class FormFactoryTest extends TestCase
{
    private SymfonyFormFactoryInterface $formFactory;

    private FormFactory $formFactoryInstance;

    protected function setUp(): void
    {
        $this->formFactory = $this->createMock(SymfonyFormFactoryInterface::class);
        $this->formFactoryInstance = new FormFactory($this->formFactory);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(FormFactory::class, $this->formFactoryInstance);
    }

    public function testItCreatesAForm(): void
    {
        $operation = $this->createMock(Operation::class);
        $form = $this->createMock(FormInterface::class);

        $operation->method('getFormType')->willReturn('App\Form\DummyType');
        $operation->method('getFormOptions')->willReturn(['foo' => 'fighters']);

        $this->formFactory->method('createNamed')
            ->with('', 'App\Form\DummyType', null, ['foo' => 'fighters', 'csrf_protection' => false])
            ->willReturn($form);

        $this->assertSame($form, $this->formFactoryInstance->create($operation, new Context()));
    }

    public function testItCreatesAFormForHtmlRequest(): void
    {
        $operation = $this->createMock(Operation::class);
        $form = $this->createMock(FormInterface::class);
        $request = $this->createMock(Request::class);

        $operation->method('getFormType')->willReturn('App\Form\DummyType');
        $operation->method('getFormOptions')->willReturn(['foo' => 'fighters']);

        $request->method('getRequestFormat')->willReturn('html');

        $this->formFactory->method('create')
            ->with('App\Form\DummyType', null, ['foo' => 'fighters'])
            ->willReturn($form);

        $this->assertSame($form, $this->formFactoryInstance->create($operation, new Context(new RequestOption($request))));
    }

    public function testItThrowsAnExceptionWhenOperationHasNoFormType(): void
    {
        $operation = $this->createMock(Operation::class);

        $operation->method('getFormType')->willReturn(null);
        $operation->method('getFormOptions')->willReturn([]);
        $operation->method('getName')->willReturn('app_dummy_create');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_create" has no configured form type.');

        $this->formFactoryInstance->create($operation, new Context());
    }
}
