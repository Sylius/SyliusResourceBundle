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

namespace App\Foundry\Factory;

use App\Entity\Author;
use App\Entity\ComicBook;
use Doctrine\Persistence\Proxy;
use Zenstruck\Foundry\LazyValue;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ComicBook>
 */
final class ComicBookFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return ComicBook::class;
    }

    public function withTitle(string $title): self
    {
        return $this->with(['title' => $title]);
    }

    /**
     * @param AuthorFactory|Proxy<Author> $author
     */
    public function withAuthor(AuthorFactory|Proxy $author): self
    {
        return $this->with(['author' => $author]);
    }

    protected function defaults(): array
    {
        $author = LazyValue::memoize(fn () => AuthorFactory::createOne());

        return [
            'title' => ucfirst(self::faker()->words(2, true)),
            'author' => $author,
        ];
    }
}
