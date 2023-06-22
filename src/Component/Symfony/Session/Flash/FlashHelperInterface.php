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

namespace Sylius\Component\Resource\Symfony\Session\Flash;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Symfony\EventDispatcher\GenericEvent;

interface FlashHelperInterface
{
    public function addSuccessFlash(Operation $operation, Context $context): void;

    public function addFlashFromEvent(GenericEvent $event, Context $context): void;
}
