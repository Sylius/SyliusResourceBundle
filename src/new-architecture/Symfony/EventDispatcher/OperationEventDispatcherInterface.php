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

interface OperationEventDispatcherInterface
{
    public function dispatch(mixed $data, Operation $operation, Context $context): OperationEvent;

    public function dispatchBulkEvent(mixed $data, Operation $operation, Context $context): OperationEvent;

    public function dispatchPreEvent(mixed $data, Operation $operation, Context $context): OperationEvent;

    public function dispatchPostEvent(mixed $data, Operation $operation, Context $context): OperationEvent;

    public function dispatchInitializeEvent(mixed $data, Operation $operation, Context $context): OperationEvent;
}
