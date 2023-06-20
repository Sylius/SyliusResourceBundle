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

namespace Sylius\Component\Resource\Generator;

use Webmozart\Assert\Assert;

final class RandomnessGenerator implements RandomnessGeneratorInterface
{
    private string $uriSafeAlphabet;

    private string $digits;

    public function __construct()
    {
        $this->digits = implode(range(0, 9));

        $this->uriSafeAlphabet =
            implode(range(0, 9))
            . implode(range('a', 'z'))
            . implode(range('A', 'Z'))
            . implode(['-', '_', '~'])
        ;
    }

    public function generateUriSafeString(int $length): string
    {
        return $this->generateStringOfLength($length, $this->uriSafeAlphabet);
    }

    public function generateNumeric(int $length): string
    {
        return $this->generateStringOfLength($length, $this->digits);
    }

    public function generateInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    private function generateStringOfLength(int $length, string $alphabet): string
    {
        $alphabetMaxIndex = strlen($alphabet) - 1;

        Assert::greaterThanEq($alphabetMaxIndex, 1);

        $randomString = '';

        for ($i = 0; $i < $length; ++$i) {
            $index = random_int(0, $alphabetMaxIndex);
            $randomString .= $alphabet[$index];
        }

        return $randomString;
    }
}
