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

use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Resource\Context\Context;

/**
 * Retrieves data from a persistence layer.
 *
 * @experimental
 */
interface ProviderInterface
{
    public function provide(Operation $operation, Context $context): object|iterable|null;
}
