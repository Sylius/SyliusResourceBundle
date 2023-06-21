<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Factory;

use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class BookFactory implements BookFactoryInterface
{
    private FactoryInterface $factory;

    private TranslationLocaleProviderInterface $localeProvider;

    public function __construct(FactoryInterface $factory, TranslationLocaleProviderInterface $localeProvider)
    {
        $this->factory = $factory;
        $this->localeProvider = $localeProvider;
    }

    public function createNew()
    {
        $book = $this->factory->createNew();

        if (!$book instanceof TranslatableInterface) {
            throw new UnexpectedTypeException($book, TranslatableInterface::class);
        }

        $book->setCurrentLocale($this->localeProvider->getDefaultLocaleCode());
        $book->setFallbackLocale($this->localeProvider->getDefaultLocaleCode());

        return $book;
    }
}
