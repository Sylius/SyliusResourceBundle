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

    /**
     * @param array{message?: string, attributePath?: string, groups?: array<string>|null, payload?: mixed}|null $options
     */
    #[HasNamedArguments]
    public function __construct(
        ?array $options = null,
        string $message = 'This code must be unique within this collection.',
        string $attributePath = 'code',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        if (\is_array($options)) {
            trigger_deprecation(
                'sylius/resource-bundle',
                '1.14',
                'Passing an array of options to configure the "%s" constraint is deprecated and will be removed in 2.0, use named arguments instead.',
                static::class,
            );

            $message = $options['message'] ?? $message;
            $attributePath = $options['attributePath'] ?? $attributePath;
            $groups ??= $options['groups'] ?? null;
            $payload ??= $options['payload'] ?? null;
        }

        parent::__construct(groups: $groups, payload: $payload);

        $this->message = $message;
        $this->attributePath = $attributePath;
    }

    public function validatedBy(): string
    {
        return UniqueWithinCollectionConstraintValidator::class;
    }
}
