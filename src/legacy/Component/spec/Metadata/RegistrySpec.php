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

namespace spec\Sylius\Component\Resource\Metadata;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\RegistryInterface as LegacyRegistryInterface;
use Sylius\Resource\Metadata\Registry as NewRegistry;
use Sylius\Resource\Metadata\RegistryInterface;

final class RegistrySpec extends ObjectBehavior
{
    function it_implements_registry_interface(): void
    {
        $this->shouldImplement(RegistryInterface::class);
    }

    function it_implements_legacy_registry_interface(): void
    {
        $this->shouldImplement(LegacyRegistryInterface::class);
    }

    function it_should_be_an_alias_of_registry(): void
    {
        $this->shouldBeAnInstanceOf(NewRegistry::class);
    }
}
