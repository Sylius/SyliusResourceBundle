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

namespace spec\Sylius\Component\Resource\Translation\Provider;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface as LegacyTranslationLocaleProviderInterface;
use Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider as NewImmutableTranslationLocaleProvider;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class ImmutableTranslationLocaleProviderSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(['pl_PL', 'en_US'], 'pl_PL');
    }

    function it_implements_translation_locale_provider_interface(): void
    {
        $this->shouldImplement(TranslationLocaleProviderInterface::class);
    }

    function it_implements_legacy_translation_locale_provider_interface(): void
    {
        $this->shouldImplement(LegacyTranslationLocaleProviderInterface::class);
    }

    function it_is_an_alias_of_immutable_translation_locale_provider(): void
    {
        $this->shouldHaveType(NewImmutableTranslationLocaleProvider::class);
    }
}
