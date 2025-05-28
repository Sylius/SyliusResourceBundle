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
use App\Foundry\Factory\BookTranslationFactory;
use Zenstruck\Foundry\Story;

final class DefaultBooksStory extends Story
{
    public function build(): void
    {
        BookFactory::new()
            ->withTranslations([
                BookTranslationFactory::new()
                    ->withLocale('en_US')
                    ->withTitle('Lord of The Rings'),
                BookTranslationFactory::new()
                    ->withLocale('pl_PL')
                    ->withTitle('Władca Pierścieni'),
            ])
            ->withAuthor('J.R.R. Tolkien')
            ->create()
        ;

        BookFactory::new()
            ->withTitle('Game of Thrones')
            ->withAuthor('George R. R. Martin')
            ->create()
        ;
    }
}
