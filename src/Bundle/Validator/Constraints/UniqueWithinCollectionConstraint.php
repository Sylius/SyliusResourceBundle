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

namespace Sylius\Bundle\ResourceBundle\Validator\Constraints;

use Sylius\Bundle\ResourceBundle\Validator\UniqueWithinCollectionConstraintValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class UniqueWithinCollectionConstraint extends Constraint
{
    public string $message = 'This code must be unique within this collection.';

    public string $attributePath = 'code';

    #[HasNamedArguments]
    public function __construct(
        string $message = 'This code must be unique within this collection.',
        string $attributePath = 'code',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(groups: $groups, payload: $payload);

        $this->message = $message;
        $this->attributePath = $attributePath;
    }

    public function validatedBy(): string
    {
        return UniqueWithinCollectionConstraintValidator::class;
    }
}
