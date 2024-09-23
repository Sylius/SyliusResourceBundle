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
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Registry;
use Sylius\Resource\Metadata\RegistryInterface;

final class RegistryTest extends TestCase
{
    use ProphecyTrait;

    private Registry $registry;

    protected function setUp(): void
    {
        $this->registry = new Registry();
    }

    public function testItImplementsRegistryInterface(): void
    {
        $this->assertInstanceOf(RegistryInterface::class, $this->registry);
    }

    public function testItReturnsAllResourcesMetadata(): void
    {
        $metadata1 = $this->prophesize(MetadataInterface::class);
        $metadata2 = $this->prophesize(MetadataInterface::class);

        $metadata1->getAlias()->willReturn('app.product');
        $metadata2->getAlias()->willReturn('app.order');

        $this->registry->add($metadata1->reveal());
        $this->registry->add($metadata2->reveal());

        $this->assertSame([
            'app.product' => $metadata1->reveal(),
            'app.order' => $metadata2->reveal(),
        ], $this->registry->getAll());
    }

    public function testItThrowsAnExceptionIfResourceIsNotRegistered(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->registry->get('foo.bar');
    }

    public function testItReturnsSpecificMetadata(): void
    {
        $metadata = $this->prophesize(MetadataInterface::class);
        $metadata->getAlias()->willReturn('app.shipping_method');

        $this->registry->add($metadata->reveal());

        $this->assertSame($metadata->reveal(), $this->registry->get('app.shipping_method'));
    }

    public function testItThrowsAnExceptionIfResourceIsNotRegisteredWithClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->registry->getByClass('App\Model\OrderItem');
    }

    public function testItReturnsSpecificMetadataByModelClass(): void
    {
        $metadata1 = $this->prophesize(MetadataInterface::class);
        $metadata2 = $this->prophesize(MetadataInterface::class);

        $metadata1->getAlias()->willReturn('app.product');
        $metadata1->getClass('model')->willReturn('App\Model\Product');

        $metadata2->getAlias()->willReturn('app.order');
        $metadata2->getClass('model')->willReturn('App\Model\Order');

        $this->registry->add($metadata1->reveal());
        $this->registry->add($metadata2->reveal());

        $this->assertSame($metadata2->reveal(), $this->registry->getByClass('App\Model\Order'));
    }

    public function testItAddsMetadataFromConfigurationArray(): void
    {
        $this->registry->addFromAliasAndConfiguration('app.product', [
            'driver' => 'doctrine/orm',
            'classes' => [
                'model' => 'App\Model\Product',
            ],
        ]);

        $this->assertInstanceOf(MetadataInterface::class, $this->registry->get('app.product'));
        $this->assertInstanceOf(MetadataInterface::class, $this->registry->getByClass('App\Model\Product'));
    }
}
