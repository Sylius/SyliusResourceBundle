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

namespace Sylius\Component\Resource\Tests\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Translation\Provider\ImmutableTranslationLocaleProvider;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface as LegacyTranslationLocaleProviderInterface;
use Sylius\Resource\Translation\Provider\ImmutableTranslationLocaleProvider as NewImmutableTranslationLocaleProvider;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class ImmutableTranslationLocaleProviderTest extends TestCase
{
    private ImmutableTranslationLocaleProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new ImmutableTranslationLocaleProvider(['pl_PL', 'en_US'], 'pl_PL');
    }

    public function testItImplementsTranslationLocaleProviderInterface(): void
    {
        $this->assertInstanceOf(TranslationLocaleProviderInterface::class, $this->provider);
    }

    public function testItImplementsLegacyTranslationLocaleProviderInterface(): void
    {
        $this->assertInstanceOf(LegacyTranslationLocaleProviderInterface::class, $this->provider);
    }

    public function testItIsAnAliasOfImmutableTranslationLocaleProvider(): void
    {
        $this->assertInstanceOf(NewImmutableTranslationLocaleProvider::class, $this->provider);
    }
}
