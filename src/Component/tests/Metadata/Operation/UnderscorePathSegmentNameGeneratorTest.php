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

namespace Sylius\Resource\Tests\Metadata\Operation;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Operation\UnderscorePathSegmentNameGenerator;

#[CoversClass(UnderscorePathSegmentNameGenerator::class)]
final class UnderscorePathSegmentNameGeneratorTest extends TestCase
{
    #[DataProvider('segmentNameProvider')]
    public function testGettingSegmentName(string $expected, string $name, bool $pluralize): void
    {
        $this->assertSame($expected, (new UnderscorePathSegmentNameGenerator())->getSegmentName($name, $pluralize));
    }

    public static function segmentNameProvider(): iterable
    {
        yield ['stephen_king_book', 'StephenKingBook', false];
        yield ['stephen_king_book', 'Stephen_King_Book', false];
        yield ['stephen_king_book', 'Stephen King Book', false];
        yield ['stephen_king_books', 'StephenKingBook', true];
        yield ['stephen_king_books', 'Stephen_King_Book', true];
        yield ['stephen_king_books', 'Stephen King Book', true];
    }
}
