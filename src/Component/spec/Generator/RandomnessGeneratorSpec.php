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

namespace spec\Sylius\Resource\Generator;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;

final class RandomnessGeneratorSpec extends ObjectBehavior
{
    function it_implements_randomness_generator_interface(): void
    {
        $this->shouldImplement(RandomnessGeneratorInterface::class);
    }

    function it_generates_random_uri_safe_string_of_length(): void
    {
        $length = 9;

        $this->generateUriSafeString($length)->shouldBeString();
        $this->generateUriSafeString($length)->shouldHaveLength($length);
    }

    function it_generates_random_numeric_string_of_length(): void
    {
        $length = 12;

        $this->generateNumeric($length)->shouldBeString();
        $this->generateNumeric($length)->shouldBeNumeric();
        $this->generateNumeric($length)->shouldHaveLength($length);
    }

    function it_generates_random_int_in_range(): void
    {
        $min = 12;
        $max = 2000000;

        $this->generateInt($min, $max)->shouldBeInt();
        $this->generateInt($min, $max)->shouldBeInRange($min, $max);
    }

    public function getMatchers(): array
    {
        return [
            'haveLength' => function (string $subject, int $length): bool {
                return $length === strlen($subject);
            },
            'beInRange' => function (int $subject, int $min, int $max): bool {
                return $subject >= $min && $subject <= $max;
            },
        ];
    }
}
