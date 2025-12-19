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

namespace Sylius\Component\Resource\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Metadata\Registry;
use Sylius\Component\Resource\Metadata\RegistryInterface as LegacyRegistryInterface;
use Sylius\Resource\Metadata\Registry as NewRegistry;
use Sylius\Resource\Metadata\RegistryInterface;

final class RegistryTest extends TestCase
{
    private Registry $registry;

    protected function setUp(): void
    {
        $this->registry = new Registry();
    }

    public function testItImplementsRegistryInterface(): void
    {
        $this->assertInstanceOf(RegistryInterface::class, $this->registry);
    }

    public function testItImplementsLegacyRegistryInterface(): void
    {
        $this->assertInstanceOf(LegacyRegistryInterface::class, $this->registry);
    }

    public function testItShouldBeAnAliasOfRegistry(): void
    {
        $this->assertInstanceOf(NewRegistry::class, $this->registry);
    }
}
