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

namespace Sylius\Resource\Context\Option;

use Sylius\Resource\Metadata\Operation;

/**
 * @experimental
 */
final class OperationOption
{
    public function __construct(private Operation $operation)
    {
    }

    public function operation(): Operation
    {
        return $this->operation;
    }
}
