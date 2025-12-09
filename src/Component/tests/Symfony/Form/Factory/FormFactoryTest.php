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
    private SymfonyFormFactoryInterface $symfonyFormFactory;

    private FormFactory $formFactory;

    protected function setUp(): void
    {
        $this->symfonyFormFactory = $this->createMock(SymfonyFormFactoryInterface::class);
        $this->formFactory = new FormFactory($this->symfonyFormFactory);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(FormFactory::class, $this->formFactory);
    }

    public function testItCreatesAForm(): void
    {
        $operation = $this->createMock(Operation::class);
        $form = $this->createMock(FormInterface::class);

        $operation->method('getFormType')->willReturn('App\Form\DummyType');
        $operation->method('getFormOptions')->willReturn(['foo' => 'fighters']);

        $this->symfonyFormFactory
            ->expects($this->once())
            ->method('createNamed')
            ->with('', 'App\Form\DummyType', null, ['foo' => 'fighters', 'csrf_protection' => false])
            ->willReturn($form);

        $result = $this->formFactory->create($operation, new Context());

        $this->assertSame($form, $result);
    }

    public function testItCreatesAFormFormHtmlRequest(): void
    {
        $operation = $this->createMock(Operation::class);
        $form = $this->createMock(FormInterface::class);
        $request = $this->createMock(Request::class);

        $operation->method('getFormType')->willReturn('App\Form\DummyType');
        $operation->method('getFormOptions')->willReturn(['foo' => 'fighters']);
        $request->method('getRequestFormat')->willReturn('html');

        $this->symfonyFormFactory
            ->expects($this->once())
            ->method('create')
            ->with('App\Form\DummyType', null, ['foo' => 'fighters'])
            ->willReturn($form);

        $result = $this->formFactory->create($operation, new Context(new RequestOption($request)));

        $this->assertSame($form, $result);
    }

    public function testItThrowsAnExceptionWhenOperationHasNoFormType(): void
    {
        $operation = $this->createMock(Operation::class);

        $operation->method('getFormType')->willReturn(null);
        $operation->method('getFormOptions')->willReturn([]);
        $operation->method('getName')->willReturn('app_dummy_create');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_create" has no configured form type.');

        $this->formFactory->create($operation, new Context());
    }
}
