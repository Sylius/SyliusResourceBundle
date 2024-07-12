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
 * @experimental
 */
interface ResponderInterface
{
    /**
     * Handle the response.
     */
    public function respond(mixed $data, Operation $operation, Context $context): mixed;
}
