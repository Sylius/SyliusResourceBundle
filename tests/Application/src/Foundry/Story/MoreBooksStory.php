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

namespace App\Foundry\Story;

use App\Foundry\Factory\BookFactory;
use Zenstruck\Foundry\Story;

final class MoreBooksStory extends Story
{
    public function build(): void
    {
        BookFactory::createSequence(
            function () {
                foreach (range(1, 22) as $number) {
                    yield ['title' => 'Book ' . $number];
                }
            },
        );
    }
}
