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

namespace Sylius\Component\Resource\Symfony\EventDispatcher;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;

interface OperationEventDispatcherInterface
{
    public function dispatch(mixed $data, Operation $operation, Context $context): void;

    public function dispatchBulkEvent(mixed $data, Operation $operation, Context $context): void;

    public function dispatchPreEvent(mixed $data, Operation $operation, Context $context): void;

    public function dispatchPostEvent(mixed $data, Operation $operation, Context $context): void;

    public function dispatchInitializeEvent(mixed $data, Operation $operation, Context $context): void;
}
