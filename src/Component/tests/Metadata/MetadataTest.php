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

namespace Sylius\Resource\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\MetadataInterface;

final class MetadataTest extends TestCase
{
    use ProphecyTrait;

    private Metadata $metadata;

    protected function setUp(): void
    {
        $this->metadata = Metadata::fromAliasAndConfiguration(
            'app.product',
            [
                'driver' => 'doctrine/orm',
                'state_machine_component' => 'symfony',
                'templates' => 'product',
                'classes' => [
                    'model' => 'App\Model\Resource',
                    'form' => [
                        'default' => 'App\Form\Type\ResourceType',
                        'choice' => 'App\Form\Type\ResourceChoiceType',
                        'autocomplete' => 'App\Type\ResourceAutocompleteType',
                    ],
                ],
            ],
        );
    }

    public function testItImplementsMetadataInterface(): void
    {
        $this->assertInstanceOf(MetadataInterface::class, $this->metadata);
    }

    public function testItHasAlias(): void
    {
        $this->assertSame('app.product', $this->metadata->getAlias());
    }

    public function testItAllowsToHaveAliasWithDotInName(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration(
            'app.product.with.dots',
            [
                'driver' => 'doctrine/orm',
                'templates' => 'product',
                'classes' => [
                    'model' => 'App\Model\Resource',
                    'form' => [
                        'default' => 'App\Form\Type\ResourceType',
                        'choice' => 'App\Form\Type\ResourceChoiceType',
                        'autocomplete' => 'App\Type\ResourceAutocompleteType',
                    ],
                ],
            ],
        );

        $this->assertSame('app.product.with.dots', $metadata->getAlias());
        $this->assertSame('app', $metadata->getApplicationName());
        $this->assertSame('product.with.dots', $metadata->getName());
    }

    public function testItHasApplicationName(): void
    {
        $this->assertSame('app', $this->metadata->getApplicationName());
    }

    public function testItHasResourceName(): void
    {
        $this->assertSame('product', $this->metadata->getName());
    }

    public function testItHumanizesSimpleNames(): void
    {
        $this->assertSame('product', $this->metadata->getHumanizedName());
    }

    public function testItHumanizesMoreComplexNames(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.product_option', ['driver' => 'doctrine/orm']);

        $this->assertSame('product option', $metadata->getHumanizedName());
    }

    public function testItHasPluralResourceName(): void
    {
        $this->assertSame('products', $this->metadata->getPluralName());
    }

    public function testItHasDriver(): void
    {
        $this->assertSame('doctrine/orm', $this->metadata->getDriver());
    }

    public function testItHasStateMachineComponent(): void
    {
        $this->assertSame('symfony', $this->metadata->getStateMachineComponent());
    }

    public function testItHasTemplatesNamespace(): void
    {
        $this->assertSame('product', $this->metadata->getTemplatesNamespace());
    }

    public function testItHasAccessToSpecificConfigParameter(): void
    {
        $this->assertSame('doctrine/orm', $this->metadata->getParameter('driver'));
    }

    public function testItChecksIfSpecificParameterExists(): void
    {
        $this->assertFalse($this->metadata->hasParameter('foo'));
        $this->assertTrue($this->metadata->hasParameter('driver'));
    }

    public function testItThrowsAnExceptionWhenParameterDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->metadata->getParameter('foo');
    }

    public function testItHasAccessToSpecificClasses(): void
    {
        $this->assertSame('App\Model\Resource', $this->metadata->getClass('model'));
    }

    public function testItThrowsAnExceptionWhenClassDoesNotExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->metadata->getClass('foo');
    }

    public function testItChecksIfSpecificClassExists(): void
    {
        $this->assertFalse($this->metadata->hasClass('bar'));
        $this->assertTrue($this->metadata->hasClass('model'));
    }

    public function testItGeneratesServiceId(): void
    {
        $this->assertSame('app.factory.product', $this->metadata->getServiceId('factory'));
        $this->assertSame('app.repository.product', $this->metadata->getServiceId('repository'));
        $this->assertSame('app.form.type.product', $this->metadata->getServiceId('form.type'));
    }

    public function testItGeneratesPermissionCode(): void
    {
        $this->assertSame('app.product.show', $this->metadata->getPermissionCode('show'));
        $this->assertSame('app.product.create', $this->metadata->getPermissionCode('create'));
        $this->assertSame('app.product.custom', $this->metadata->getPermissionCode('custom'));
    }
}
