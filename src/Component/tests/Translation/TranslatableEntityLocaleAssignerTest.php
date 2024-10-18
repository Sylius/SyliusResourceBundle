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

namespace Sylius\Resource\Tests\Translation;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Resource\Translation\TranslatableEntityLocaleAssigner;
use Sylius\Resource\Translation\TranslatableEntityLocaleAssignerInterface;

final class TranslatableEntityLocaleAssignerTest extends TestCase
{
    private TranslationLocaleProviderInterface $translationLocaleProvider;

    private TranslatableEntityLocaleAssigner $localeAssigner;

    protected function setUp(): void
    {
        $this->translationLocaleProvider = $this->createMock(TranslationLocaleProviderInterface::class);
        $this->localeAssigner = new TranslatableEntityLocaleAssigner($this->translationLocaleProvider);
    }

    public function testItImplementsTranslatableEntityLocaleAssignerInterface(): void
    {
        $this->assertInstanceOf(TranslatableEntityLocaleAssignerInterface::class, $this->localeAssigner);
    }

    public function testItAssignsCurrentAndDefaultLocaleToGivenTranslatableEntity(): void
    {
        $translatableEntity = $this->createMock(TranslatableInterface::class);

        $this->translationLocaleProvider
            ->method('getDefaultLocaleCode')
            ->willReturn('en_US');

        $translatableEntity->expects($this->once())
            ->method('setCurrentLocale')
            ->with('en_US');

        $translatableEntity->expects($this->once())
            ->method('setFallbackLocale')
            ->with('en_US');

        $this->localeAssigner->assignLocale($translatableEntity);
    }
}
