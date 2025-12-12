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

namespace Sylius\Component\Resource\Tests\Translation;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssigner;
use Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssignerInterface as LegacyTranslatableEntityLocaleAssignerInterface;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Resource\Translation\TranslatableEntityLocaleAssigner as NewTranslatableEntityLocaleAssigner;
use Sylius\Resource\Translation\TranslatableEntityLocaleAssignerInterface;

final class TranslatableEntityLocaleAssignerTest extends TestCase
{
    private TranslatableEntityLocaleAssigner $assigner;

    protected function setUp(): void
    {
        $translationLocaleProvider = $this->createMock(TranslationLocaleProviderInterface::class);
        $this->assigner = new TranslatableEntityLocaleAssigner($translationLocaleProvider);
    }

    public function testItImplementsTranslatableEntityLocaleAssignerInterface(): void
    {
        $this->assertInstanceOf(TranslatableEntityLocaleAssignerInterface::class, $this->assigner);
    }

    public function testItImplementsLegacyTranslatableEntityLocaleAssignerInterface(): void
    {
        $this->assertInstanceOf(LegacyTranslatableEntityLocaleAssignerInterface::class, $this->assigner);
    }

    public function testItIsAnAliasOfTranslatableEntityLocalAssigner(): void
    {
        $this->assertInstanceOf(NewTranslatableEntityLocaleAssigner::class, $this->assigner);
    }
}
