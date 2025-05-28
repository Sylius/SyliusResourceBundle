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

use App\Foundry\Factory\AuthorFactory;
use App\Foundry\Factory\ComicBookFactory;
use Zenstruck\Foundry\Story;

final class DefaultComicBooksStory extends Story
{
    public function build(): void
    {
        ComicBookFactory::new()
            ->withTitle('Old Man Logan')
            ->withAuthor(
                AuthorFactory::new()
                ->withFirstName('Andrea')
                ->withLastName('Sorrentino'),
            )
            ->create()
        ;

        ComicBookFactory::new()
            ->withTitle('Civil War II')
            ->withAuthor(
                AuthorFactory::new()
                ->withFirstName('Brian Michael')
                ->withLastName('Bendis'),
            )
            ->create()
        ;
    }
}
