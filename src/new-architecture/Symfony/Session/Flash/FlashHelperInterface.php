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

namespace Sylius\Resource\Symfony\Session\Flash;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;

interface FlashHelperInterface
{
    public function addSuccessFlash(Operation $operation, Context $context): void;

    public function addFlashFromEvent(GenericEvent $event, Context $context): void;
}
