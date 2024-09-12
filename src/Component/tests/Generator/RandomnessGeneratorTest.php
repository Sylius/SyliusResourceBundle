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

namespace Sylius\Resource\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Generator\RandomnessGenerator;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;

final class RandomnessGeneratorTest extends TestCase
{
    private RandomnessGeneratorInterface $generator;

    protected function setUp(): void
    {
        $this->generator = new RandomnessGenerator();
    }

    public function testItImplementsRandomnessGeneratorInterface(): void
    {
        $this->assertInstanceOf(RandomnessGeneratorInterface::class, $this->generator);
    }

    public function testItGeneratesRandomUriSafeStringOfLength(): void
    {
        $length = 9;
        $result = $this->generator->generateUriSafeString($length);

        $this->assertIsString($result);
        $this->assertSame($length, strlen($result));
    }

    public function testItGeneratesRandomNumericStringOfLength(): void
    {
        $length = 12;
        $result = $this->generator->generateNumeric($length);

        $this->assertIsString($result);
        $this->assertIsNumeric($result);
        $this->assertSame($length, strlen($result));
    }

    public function testItGeneratesRandomIntInRange(): void
    {
        $min = 12;
        $max = 2000000;
        $result = $this->generator->generateInt($min, $max);

        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual($min, $result);
        $this->assertLessThanOrEqual($max, $result);
    }
}
