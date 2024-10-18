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

namespace Sylius\Resource\Tests\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class ImmutableTranslationLocaleProviderTest extends TestCase
{
    private ImmutableTranslationLocaleProvider $localeProvider;

    protected function setUp(): void
    {
        $this->localeProvider = new ImmutableTranslationLocaleProvider(['pl_PL', 'en_US'], 'pl_PL');
    }

    public function testItImplementsTranslationLocaleProviderInterface(): void
    {
        $this->assertInstanceOf(TranslationLocaleProviderInterface::class, $this->localeProvider);
    }

    public function testItReturnsDefinedLocalesCodes(): void
    {
        $this->assertSame(['pl_PL', 'en_US'], $this->localeProvider->getDefinedLocalesCodes());
    }

    public function testItReturnsTheDefaultLocaleCode(): void
    {
        $this->assertSame('pl_PL', $this->localeProvider->getDefaultLocaleCode());
    }
}
