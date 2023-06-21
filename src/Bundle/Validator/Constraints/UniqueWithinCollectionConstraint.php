<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Validator\Constraints;

use Sylius\Bundle\ResourceBundle\Validator\UniqueWithinCollectionConstraintValidator;
use Symfony\Component\Validator\Constraint;

final class UniqueWithinCollectionConstraint extends Constraint
{
    public string $message = 'This code must be unique within this collection.';

    public string $attributePath = 'code';

    public function validatedBy(): string
    {
        return UniqueWithinCollectionConstraintValidator::class;
    }
}
