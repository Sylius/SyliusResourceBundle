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

namespace spec\Sylius\Component\Resource\Translation;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssignerInterface as LegacyTranslatableEntityLocaleAssignerInterface;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Resource\Translation\TranslatableEntityLocaleAssigner as NewTranslatableEntityLocaleAssigner;
use Sylius\Resource\Translation\TranslatableEntityLocaleAssignerInterface;

final class TranslatableEntityLocaleAssignerSpec extends ObjectBehavior
{
    function let(TranslationLocaleProviderInterface $translationLocaleProvider): void
    {
        $this->beConstructedWith($translationLocaleProvider);
    }

    function it_implements_translatable_entity_locale_assigner_interface(): void
    {
        $this->shouldImplement(TranslatableEntityLocaleAssignerInterface::class);
    }

    function it_implements_legacy_translatable_entity_locale_assigner_interface(): void
    {
        $this->shouldImplement(LegacyTranslatableEntityLocaleAssignerInterface::class);
    }

    function it_is_an_alias_of_translatable_entity_local_assigner(): void
    {
        $this->shouldHaveType(NewTranslatableEntityLocaleAssigner::class);
    }
}
