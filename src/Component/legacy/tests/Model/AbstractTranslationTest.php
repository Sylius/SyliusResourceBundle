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

namespace Sylius\Component\Resource\Tests\Model;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Resource\Model\AbstractTranslation as NewAbstractTranslation;
use Sylius\Resource\Model\TranslationInterface;

final class AbstractTranslationTest extends TestCase
{
    private ConcreteTranslation $translation;

    protected function setUp(): void
    {
        $this->translation = new ConcreteTranslation();
    }

    public function testItIsATranslation(): void
    {
        $this->assertInstanceOf(TranslationInterface::class, $this->translation);
    }

    public function testItShouldBeAnAliasOfAbstractTranslation(): void
    {
        $this->assertInstanceOf(NewAbstractTranslation::class, $this->translation);
    }
}

class ConcreteTranslation extends AbstractTranslation
{
}
