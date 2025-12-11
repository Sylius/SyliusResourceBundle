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

namespace Sylius\Resource\Tests\Factory;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Factory\TranslatableFactory;
use Sylius\Resource\Factory\TranslatableFactoryInterface;
use Sylius\Resource\Model\TranslatableInterface;
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
    public function it_throws_an_exception_if_resource_is_not_translatable(): void
    {
        $this->factory->method('createNew')->willReturn(new \stdClass());

        $this->expectException(UnexpectedTypeException::class);

        $this->translatableFactory->createNew();
    }

    /** @test */
    public function it_creates_translatable_and_sets_locales(): void
    {
        $resource = $this->createMock(TranslatableInterface::class);

        $this->localeProvider->method('getDefaultLocaleCode')->willReturn('pl_PL');

        $this->factory->method('createNew')->willReturn($resource);

        $resource->expects($this->once())->method('setCurrentLocale')->with('pl_PL');
        $resource->expects($this->once())->method('setFallbackLocale')->with('pl_PL');

        $this->assertEquals($resource, $this->translatableFactory->createNew());
    }
}
