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

namespace Sylius\Component\Resource\Exception;

class RaceConditionException extends UpdateHandlingException
{
    public function __construct(?\Exception $previous = null)
    {
        parent::__construct(
            'Operated entity was previously modified.',
            'race_condition_error',
            409,
            null !== $previous ? (int) $previous->getCode() : 0,
            $previous,
        );
    }
}
