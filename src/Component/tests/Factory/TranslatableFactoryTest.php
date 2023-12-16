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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Factory\TranslatableFactory;
use Sylius\Resource\Factory\TranslatableFactoryInterface;
use Sylius\Resource\Model\TranslatableInterface;

final class TranslatableFactoryTest extends TestCase
{
    use ProphecyTrait;

    private FactoryInterface|ObjectProphecy $factory;

    private TranslationLocaleProviderInterface|ObjectProphecy $localeProvider;

    private TranslatableFactory $translatableFactory;

    protected function setUp(): void
    {
        $this->factory = $this->prophesize(FactoryInterface::class);
        $this->localeProvider = $this->prophesize(TranslationLocaleProviderInterface::class);

        $this->translatableFactory = new TranslatableFactory($this->factory->reveal(), $this->localeProvider->reveal());
    }

    /** @test */
    public function it_implements_translatable_factory_interface(): void
    {
        $this->assertInstanceOf(TranslatableFactoryInterface::class, $this->translatableFactory);
    }

    /** @test */
    public function it_throws_an_exception_if_resource_is_not_translatable(): void
    {
        $this->factory->createNew()->willReturn(new \stdClass());

        $this->expectException(UnexpectedTypeException::class);

        $this->translatableFactory->createNew();
    }

    /** @test */
    public function it_creates_translatable_and_sets_locales(): void
    {
        $resource = $this->prophesize(TranslatableInterface::class);

        $this->localeProvider->getDefaultLocaleCode()->willReturn('pl_PL');

        $this->factory->createNew()->willReturn($resource);

        $resource->setCurrentLocale('pl_PL')->shouldBeCalled();
        $resource->setFallbackLocale('pl_PL')->shouldBeCalled();

        $this->assertEquals($resource->reveal(), $this->translatableFactory->createNew());
    }
}
