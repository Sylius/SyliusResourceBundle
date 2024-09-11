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
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Model\AbstractTranslation;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TranslationInterface;

final class AbstractTranslationTest extends TestCase
{
    use ProphecyTrait;

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
        $translatable = $this->prophesize(TranslatableInterface::class);

        $this->translation->setTranslatable($translatable->reveal());
        $this->assertSame($translatable->reveal(), $this->translation->getTranslatable());
    }

    public function testItsDetachesFromItsTranslatableCorrectly(): void
    {
        $translatable1 = $this->prophesize(TranslatableInterface::class);
        $translatable2 = $this->prophesize(TranslatableInterface::class);

        $translatable1->addTranslation(Argument::type(AbstractTranslation::class))->shouldBeCalled();
        $this->translation->setTranslatable($translatable1->reveal());

        $translatable1->removeTranslation(Argument::type(AbstractTranslation::class))->shouldBeCalled();
        $translatable2->addTranslation(Argument::type(AbstractTranslation::class))->shouldBeCalled();
        $this->translation->setTranslatable($translatable2->reveal());
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
