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

namespace Sylius\Resource\State;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;

/**
 * Process data: send an email, persist to storage, add to queue etc.
 *
 * @experimental
 */
interface ProcessorInterface
{
    /**
     * Handle the state.
     */
    public function process(mixed $data, Operation $operation, Context $context): mixed;
}
