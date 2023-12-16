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

namespace Sylius\Component\Resource\Translation;

use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Resource\Model\TranslatableInterface;

final class TranslatableEntityLocaleAssigner implements TranslatableEntityLocaleAssignerInterface
{
    private TranslationLocaleProviderInterface $translationLocaleProvider;

    public function __construct(TranslationLocaleProviderInterface $translationLocaleProvider)
    {
        $this->translationLocaleProvider = $translationLocaleProvider;
    }

    public function assignLocale(TranslatableInterface $translatableEntity): void
    {
        $localeCode = $this->translationLocaleProvider->getDefaultLocaleCode();

        $translatableEntity->setCurrentLocale($localeCode);
        $translatableEntity->setFallbackLocale($localeCode);
    }
}
