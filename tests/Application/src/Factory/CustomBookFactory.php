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

namespace App\Factory;

use App\Entity\Book;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class CustomBookFactory
{
    private string $className;

    private TranslationLocaleProviderInterface $localeProvider;

    public function __construct(string $className, TranslationLocaleProviderInterface $localeProvider)
    {
        $this->className = $className;
        $this->localeProvider = $localeProvider;
    }

    public function createCustom(): Book
    {
        /** @var Book $book */
        $book = new $this->className();

        $book->setCurrentLocale($this->localeProvider->getDefaultLocaleCode());
        $book->setFallbackLocale($this->localeProvider->getDefaultLocaleCode());

        return $book;
    }
}
