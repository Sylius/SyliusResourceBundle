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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\Extension;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Extension\CollectionTypeExtension;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CollectionTypeExtensionTest extends TestCase
{
    private CollectionTypeExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new CollectionTypeExtension();
    }

    public function testExtendsAbstractTypeExtension(): void
    {
        self::assertInstanceOf(AbstractTypeExtension::class, $this->extension);
    }

    public function testGetExtendedType(): void
    {
        self::assertSame(CollectionType::class, $this->extension->getExtendedType());
    }

    public function testGetExtendedTypes(): void
    {
        self::assertSame([CollectionType::class], $this->extension->getExtendedTypes());
    }

    public function testConfigureOptionsWithDefaults(): void
    {
        $resolver = new OptionsResolver();

        $this->extension->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame('sylius.form.collection.add', $options['button_add_label']);
        self::assertSame('sylius.form.collection.delete', $options['button_delete_label']);
    }

    public function testConfigureOptionsWithCustomLabels(): void
    {
        $resolver = new OptionsResolver();

        $this->extension->configureOptions($resolver);

        $options = $resolver->resolve([
            'button_add_label' => 'custom.add',
            'button_delete_label' => 'custom.delete',
        ]);

        self::assertSame('custom.add', $options['button_add_label']);
        self::assertSame('custom.delete', $options['button_delete_label']);
    }

    public function testBuildViewAddsButtonLabelsToViewVars(): void
    {
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);
        $options = [
            'button_add_label' => 'app.add_item',
            'button_delete_label' => 'app.remove_item',
        ];

        $this->extension->buildView($view, $form, $options);

        self::assertArrayHasKey('button_add_label', $view->vars);
        self::assertArrayHasKey('button_delete_label', $view->vars);
        self::assertSame('app.add_item', $view->vars['button_add_label']);
        self::assertSame('app.remove_item', $view->vars['button_delete_label']);
    }

    public function testBuildViewAddsDefaultLabelsToViewVars(): void
    {
        $view = new FormView();
        $form = $this->createMock(FormInterface::class);
        $options = [
            'button_add_label' => 'sylius.form.collection.add',
            'button_delete_label' => 'sylius.form.collection.delete',
        ];

        $this->extension->buildView($view, $form, $options);

        self::assertSame('sylius.form.collection.add', $view->vars['button_add_label']);
        self::assertSame('sylius.form.collection.delete', $view->vars['button_delete_label']);
    }
}
