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
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssignerInterface;
use Sylius\Resource\Model\TranslatableInterface;

final class TranslatableEntityLocaleAssignerSpec extends ObjectBehavior
{
    function let(TranslationLocaleProviderInterface $translationLocaleProvider): void
    {
        $this->beConstructedWith($translationLocaleProvider);
    }

    function it_implements_traslatable_entity_locale_assigner_interface(): void
    {
        $this->shouldImplement(TranslatableEntityLocaleAssignerInterface::class);
    }

    function it_should_assign_current_and_default_locale_to_given_translatable_entity(
        TranslationLocaleProviderInterface $translationLocaleProvider,
        TranslatableInterface $translatableEntity,
    ): void {
        $translationLocaleProvider->getDefaultLocaleCode()->willReturn('en_US');

        $translatableEntity->setCurrentLocale('en_US')->shouldBeCalled();
        $translatableEntity->setFallbackLocale('en_US')->shouldBeCalled();

        $this->assignLocale($translatableEntity);
    }
}
