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

namespace Sylius\Component\Resource\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\FactoryInterface as LegacyFactoryInterface;
use Sylius\Resource\Factory\Factory as NewFactory;
use Sylius\Resource\Factory\FactoryInterface;

final class FactoryTest extends TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        $this->factory = new Factory(\stdClass::class);
    }

    /** @test */
    public function it_implements_factory_interface(): void
    {
        $this->assertInstanceOf(FactoryInterface::class, $this->factory);
    }

    /** @test */
    public function it_implements_legacy_factory_interface(): void
    {
        $this->assertInstanceOf(LegacyFactoryInterface::class, $this->factory);
    }

    /** @test */
    public function it_is_an_alias_of_the_factory(): void
    {
        $this->assertInstanceOf(NewFactory::class, $this->factory);
    }

    /** @test */
    public function it_creates_a_new_instance_of_a_resource(): void
    {
        $this->assertInstanceOf(\stdClass::class, $this->factory->createNew());
    }
}
