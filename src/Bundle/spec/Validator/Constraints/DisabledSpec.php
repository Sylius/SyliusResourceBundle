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

namespace Sylius\Bundle\ResourceBundle\Tests\Validator\Constraints;

use Sylius\Bundle\ResourceBundle\Validator\Constraints\Disabled;
use Sylius\Bundle\ResourceBundle\Validator\DisabledValidator;
use Symfony\Component\Validator\Constraint;

final class DisabledTest extends ToggleableConstraintTestCase
{
    protected function createConstraint(): Constraint
    {
        return new Disabled();
    }

    protected function getExpectedValidatorClass(): string
    {
        return DisabledValidator::class;
    }
}
