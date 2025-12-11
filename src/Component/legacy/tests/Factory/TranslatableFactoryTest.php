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

namespace Sylius\Component\Resource\Tests\Factory;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Factory\TranslatableFactory;
use Sylius\Component\Resource\Factory\TranslatableFactoryInterface as LegacyTranslatableFactoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Factory\TranslatableFactory as NewTranslatableFactory;
use Sylius\Resource\Factory\TranslatableFactoryInterface;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;

final class TranslatableFactoryTest extends TestCase
{
    private FactoryInterface|MockObject $factory;

    private TranslationLocaleProviderInterface|MockObject $localeProvider;

    private TranslatableFactory $translatableFactory;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(FactoryInterface::class);
        $this->localeProvider = $this->createMock(TranslationLocaleProviderInterface::class);

        $this->translatableFactory = new TranslatableFactory($this->factory, $this->localeProvider);
    }

    /** @test */
    public function it_implements_translatable_factory_interface(): void
    {
        $this->assertInstanceOf(TranslatableFactoryInterface::class, $this->translatableFactory);
    }

    /** @test */
    public function it_implements_legacy_translatable_factory_interface(): void
    {
        $this->assertInstanceOf(LegacyTranslatableFactoryInterface::class, $this->translatableFactory);
    }

    /** @test */
    public function it_should_be_an_alias_of_translatable_factory(): void
    {
        $this->assertInstanceOf(NewTranslatableFactory::class, $this->translatableFactory);
    }
}
