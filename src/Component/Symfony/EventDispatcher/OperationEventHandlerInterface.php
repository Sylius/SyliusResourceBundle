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
use Symfony\Component\HttpFoundation\Response;

/**
 * @experimental
 */
interface OperationEventHandlerInterface
{
    public function handleEvent(
        OperationEvent $event,
        Context $context,
        ?string $newOperation = null,
    ): ?Response;
}
