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

namespace Sylius\Component\Resource\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;

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
