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

namespace Sylius\Resource\Translation\Provider;

final class ImmutableTranslationLocaleProvider implements TranslationLocaleProviderInterface
{
    public function __construct(
        private array $definedLocalesCodes,
        private string $defaultLocaleCode,
    ) {
    }

    public function getDefinedLocalesCodes(): array
    {
        return $this->definedLocalesCodes;
    }

    public function getDefaultLocaleCode(): string
    {
        return $this->defaultLocaleCode;
    }
}

class_alias(ImmutableTranslationLocaleProvider::class, \Sylius\Component\Resource\Translation\Provider\ImmutableTranslationLocaleProvider::class);
