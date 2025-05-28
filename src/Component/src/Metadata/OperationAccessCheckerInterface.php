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

namespace Sylius\Resource\Metadata;

use Sylius\Resource\Context\Context;

interface OperationAccessCheckerInterface
{
    /**
     * Checks if the current user can access the given operation.
     *
     * @param array{object?: object|array|null} $extraVariables
     */
    public function isGranted(Operation $operation, Context $context, array $extraVariables = []): bool;
}
