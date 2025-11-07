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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\Type;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AbstractResourceTypeTest extends TestCase
{
    public function testExtendsAbstractType(): void
    {
        $formType = $this->createFormType('App\Entity\Product');

        self::assertInstanceOf(AbstractType::class, $formType);
    }

    public function testConstructorSetsDataClass(): void
    {
        $formType = $this->createFormType('App\Entity\Product');
        $resolver = new OptionsResolver();

        $formType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame('App\Entity\Product', $options['data_class']);
    }

    public function testConstructorSetsValidationGroups(): void
    {
        $formType = $this->createFormType('App\Entity\Product', ['Default', 'product_create']);
        $resolver = new OptionsResolver();

        $formType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame(['Default', 'product_create'], $options['validation_groups']);
    }

    public function testConfigureOptionsWithDefaultValidationGroups(): void
    {
        $formType = $this->createFormType('App\Entity\Product');
        $resolver = new OptionsResolver();

        $formType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertArrayHasKey('validation_groups', $options);
        self::assertSame([], $options['validation_groups']);
    }

    public function testConfigureOptionsCanBeOverridden(): void
    {
        $formType = $this->createFormType('App\Entity\Product', ['Default']);
        $resolver = new OptionsResolver();

        $formType->configureOptions($resolver);

        $options = $resolver->resolve([
            'data_class' => 'App\Entity\Category',
            'validation_groups' => ['custom'],
        ]);

        self::assertSame('App\Entity\Category', $options['data_class']);
        self::assertSame(['custom'], $options['validation_groups']);
    }

    public function testConfigureOptionsWithMultipleValidationGroups(): void
    {
        $formType = $this->createFormType(
            'App\Entity\Product',
            ['Default', 'product_create', 'strict_validation'],
        );
        $resolver = new OptionsResolver();

        $formType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertSame(['Default', 'product_create', 'strict_validation'], $options['validation_groups']);
    }

    public function testConfigureOptionsSetsDataClassAsDefault(): void
    {
        $formType = $this->createFormType('App\Entity\Order');
        $resolver = new OptionsResolver();

        $formType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertArrayHasKey('data_class', $options);
        self::assertSame('App\Entity\Order', $options['data_class']);
    }

    /**
     * @param string[] $validationGroups
     */
    private function createFormType(string $dataClass, array $validationGroups = []): AbstractResourceType
    {
        return new class($dataClass, $validationGroups) extends AbstractResourceType {
        };
    }
}
