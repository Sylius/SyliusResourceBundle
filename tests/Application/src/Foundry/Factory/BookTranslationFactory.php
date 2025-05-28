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

use App\Entity\BookTranslation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<BookTranslation>
 */
final class BookTranslationFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return BookTranslation::class;
    }

    public function withLocale(string $locale): self
    {
        return $this->with(['locale' => $locale]);
    }

    public function withTitle(string $title): self
    {
        return $this->with(['title' => $title]);
    }

    protected function defaults(): array
    {
        return [
            'locale' => self::faker()->locale(),
            'title' => ucfirst(self::faker()->words(2, true)),
        ];
    }
}
