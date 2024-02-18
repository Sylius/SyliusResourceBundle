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

namespace spec\Sylius\Component\Resource\Model;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Resource\Model\AbstractTranslation as NewAbstractTranslation;
use Sylius\Resource\Model\TranslationInterface;

final class AbstractTranslationSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beAnInstanceOf('spec\Sylius\Component\Resource\Model\ConcreteTranslation');
    }

    function it_is_a_translation(): void
    {
        $this->shouldImplement(TranslationInterface::class);
    }

    function it_should_be_an_alias_of_abstract_translation(): void
    {
        $this->shouldHaveType(NewAbstractTranslation::class);
    }
}

class ConcreteTranslation extends AbstractTranslation
{
}
