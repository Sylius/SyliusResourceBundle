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

namespace spec\Sylius\Component\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Factory\TranslatableFactoryInterface as LegacyTranslatableFactoryInterface;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Factory\TranslatableFactory as NewTranslatableFactory;
use Sylius\Resource\Factory\TranslatableFactoryInterface;

final class TranslatableFactorySpec extends ObjectBehavior
{
    function let(FactoryInterface $factory, TranslationLocaleProviderInterface $localeProvider): void
    {
        $this->beConstructedWith($factory, $localeProvider);
    }

    function it_implements_translatable_factory_interface(): void
    {
        $this->shouldImplement(TranslatableFactoryInterface::class);
    }

    function it_implements_legacy_translatable_factory_interface(): void
    {
        $this->shouldImplement(LegacyTranslatableFactoryInterface::class);
    }

    function it_should_be_an_alias_of_translatable_factory(): void
    {
        $this->shouldBeAnInstanceOf(NewTranslatableFactory::class);
    }
}
