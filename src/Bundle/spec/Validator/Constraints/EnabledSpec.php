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

namespace spec\Sylius\Bundle\ResourceBundle\Validator\Constraints;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Validator\EnabledValidator;
use Symfony\Component\Validator\Constraint;

final class EnabledSpec extends ObjectBehavior
{
    function it_is_constraint(): void
    {
        $this->shouldHaveType(Constraint::class);
    }

    function it_is_a_property_constraint(): void
    {
        $this->getTargets()->shouldContain(Constraint::PROPERTY_CONSTRAINT);
    }

    function it_is_a_class_constraint(): void
    {
        $this->getTargets()->shouldContain(Constraint::CLASS_CONSTRAINT);
    }

    function it_is_validated_by_service(): void
    {
        $this->validatedBy()->shouldReturn(EnabledValidator::class);
    }
}
