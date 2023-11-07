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
use Symfony\Component\HttpFoundation\Response;

/**
 * @experimental
 */
interface OperationEventHandlerInterface
{
    public function handlePreProcessEvent(OperationEvent $event, Context $context, ?string $newOperation = null): ?Response;

    public function handlePostProcessEvent(OperationEvent $event, Context $context): ?Response;
}
