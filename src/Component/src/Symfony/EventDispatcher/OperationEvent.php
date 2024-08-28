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

namespace Sylius\Resource\Symfony\EventDispatcher;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;

/**
 * @experimental
 */
final class OperationEvent extends GenericEvent
{
    public function getOperation(): Operation
    {
        /** @var Operation $operation */
        $operation = $this->getArgument('operation');

        return $operation;
    }

    public function getContext(): Context
    {
        /** @var Context $context */
        $context = $this->getArgument('context');

        return $context;
    }
}
