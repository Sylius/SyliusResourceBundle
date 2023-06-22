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

namespace Sylius\Component\Resource\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;

/**
 * Retrieves data from a persistence layer.
 *
 * @experimental
 */
interface ProviderInterface
{
    public function provide(Operation $operation, Context $context): object|iterable|null;
}
