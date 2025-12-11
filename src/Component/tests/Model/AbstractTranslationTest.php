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

namespace Sylius\Resource\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Model\AbstractTranslation;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TranslationInterface;

final class AbstractTranslationTest extends TestCase
{
    private AbstractTranslation $translation;

    protected function setUp(): void
    {
        $this->translation = new ConcreteTranslation();
    }

    public function testItIsATranslation(): void
    {
        $this->assertInstanceOf(TranslationInterface::class, $this->translation);
    }

    public function testItsTranslatableIsMutable(): void
    {
        $translatable = $this->createMock(TranslatableInterface::class);

        $this->translation->setTranslatable($translatable);
        $this->assertSame($translatable, $this->translation->getTranslatable());
    }

    public function testItsDetachesFromItsTranslatableCorrectly(): void
    {
        $translatable1 = $this->createMock(TranslatableInterface::class);
        $translatable2 = $this->createMock(TranslatableInterface::class);

        $translatable1->expects($this->once())->method('addTranslation')->with($this->isInstanceOf(AbstractTranslation::class));
        $this->translation->setTranslatable($translatable1);

        $translatable1->expects($this->once())->method('removeTranslation')->with($this->isInstanceOf(AbstractTranslation::class));
        $translatable2->expects($this->once())->method('addTranslation')->with($this->isInstanceOf(AbstractTranslation::class));
        $this->translation->setTranslatable($translatable2);
    }

    public function testItsLocaleIsMutable(): void
    {
        $this->translation->setLocale('en');
        $this->assertSame('en', $this->translation->getLocale());
    }
}

class ConcreteTranslation extends AbstractTranslation
{
}
