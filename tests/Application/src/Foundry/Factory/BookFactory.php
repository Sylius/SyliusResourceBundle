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

use App\Entity\Book;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Book>
 */
final class BookFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Book::class;
    }

    public function withTranslations(array $translations): self
    {
        return $this->with(['translations' => $translations]);
    }

    public function withTitle(string $title): self
    {
        return $this->with(['title' => $title]);
    }

    public function withAuthor(string $author): self
    {
        return $this->with(['author' => $author]);
    }

    protected function defaults(): array
    {
        return [
            'fallbackLocale' => 'en_US',
            'currentLocale' => 'en_US',
            'author' => self::faker()->firstName() . ' ' . self::faker()->lastName(),
        ];
    }
}
