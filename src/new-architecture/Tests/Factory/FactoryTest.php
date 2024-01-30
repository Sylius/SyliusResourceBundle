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

namespace Sylius\Resource\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Factory\Factory;
use Sylius\Resource\Factory\FactoryInterface;

final class FactoryTest extends TestCase
{
    use ProphecyTrait;

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
    public function it_creates_a_new_instance_of_a_resource(): void
    {
        $this->assertInstanceOf(\stdClass::class, $this->factory->createNew());
    }
}
