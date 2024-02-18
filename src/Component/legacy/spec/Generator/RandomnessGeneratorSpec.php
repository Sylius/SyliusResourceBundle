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

namespace spec\Sylius\Component\Resource\Generator;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Generator\RandomnessGeneratorInterface as LegacyRandomnessGeneratorInterface;
use Sylius\Resource\Generator\RandomnessGenerator as NewRandomnessGenerator;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;

final class RandomnessGeneratorSpec extends ObjectBehavior
{
    function it_implements_randomness_generator_interface(): void
    {
        $this->shouldImplement(RandomnessGeneratorInterface::class);
    }

    function it_implements_legacy_randomness_generator_interface(): void
    {
        $this->shouldImplement(LegacyRandomnessGeneratorInterface::class);
    }

    function it_should_be_an_alias_of_randomness_generator(): void
    {
        $this->shouldBeAnInstanceOf(NewRandomnessGenerator::class);
    }
}
