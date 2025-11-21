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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\Registry;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistry;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;

final class FormTypeRegistryTest extends TestCase
{
    private FormTypeRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new FormTypeRegistry();
    }

    public function testImplementsFormTypeRegistryInterface(): void
    {
        self::assertInstanceOf(FormTypeRegistryInterface::class, $this->registry);
    }

    public function testAddsFormType(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');

        self::assertTrue($this->registry->has('app.product', 'default'));
    }

    public function testGetReturnsFormTypeWhenExists(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');

        self::assertSame('App\Form\ProductType', $this->registry->get('app.product', 'default'));
    }

    public function testGetReturnsNullWhenFormTypeDoesNotExist(): void
    {
        self::assertNull($this->registry->get('app.product', 'default'));
    }

    public function testHasReturnsFalseWhenFormTypeDoesNotExist(): void
    {
        self::assertFalse($this->registry->has('app.product', 'default'));
    }

    public function testHasReturnsTrueWhenFormTypeExists(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');

        self::assertTrue($this->registry->has('app.product', 'default'));
    }

    public function testSupportsMultipleIdentifiers(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');
        $this->registry->add('app.category', 'default', 'App\Form\CategoryType');

        self::assertSame('App\Form\ProductType', $this->registry->get('app.product', 'default'));
        self::assertSame('App\Form\CategoryType', $this->registry->get('app.category', 'default'));
    }

    public function testSupportsMultipleTypeIdentifiersForSameIdentifier(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');
        $this->registry->add('app.product', 'admin', 'App\Form\AdminProductType');

        self::assertSame('App\Form\ProductType', $this->registry->get('app.product', 'default'));
        self::assertSame('App\Form\AdminProductType', $this->registry->get('app.product', 'admin'));
    }

    public function testOverwritesFormTypeWhenAddingWithSameIdentifiers(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');
        $this->registry->add('app.product', 'default', 'App\Form\NewProductType');

        self::assertSame('App\Form\NewProductType', $this->registry->get('app.product', 'default'));
    }

    public function testHasReturnsFalseForDifferentTypeIdentifier(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');

        self::assertFalse($this->registry->has('app.product', 'admin'));
    }

    public function testGetReturnsNullForDifferentTypeIdentifier(): void
    {
        $this->registry->add('app.product', 'default', 'App\Form\ProductType');

        self::assertNull($this->registry->get('app.product', 'admin'));
    }
}
