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

namespace Sylius\Resource\Tests\Metadata\Inflector;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Inflector\Inflector;

#[CoversClass(Inflector::class)]
final class InflectorTest extends TestCase
{
    #[DataProvider('tableizeProvider')]
    public function testTableize(string $expected, string $string): void
    {
        $this->assertSame($expected, (new Inflector())->tableize($string));
    }

    #[DataProvider('pluralizeProvider')]
    public function testPluralize(string $expected, string $string): void
    {
        $this->assertSame($expected, (new Inflector())->pluralize($string));
    }

    #[DataProvider('dashizeProvider')]
    public function testDashize(string $expected, string $string): void
    {
        $this->assertSame($expected, (new Inflector())->dashize($string));
    }

    public static function tableizeProvider(): iterable
    {
        yield ['book', 'Book'];
        yield ['stephen_king_book', 'StephenKingBook'];
    }

    public static function pluralizeProvider(): iterable
    {
        yield ['books', 'book'];
        yield ['products', 'product'];
        yield ['orders', 'order'];
    }

    public static function dashizeProvider(): iterable
    {
        yield ['stephen-king-book', 'StephenKingBook'];
        yield ['stephen-king-book', 'Stephen_King_Book'];
        yield ['stephen-king-book', 'Stephen King Book'];
    }
}
