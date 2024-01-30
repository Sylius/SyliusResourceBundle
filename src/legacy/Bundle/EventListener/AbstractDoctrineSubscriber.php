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

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Doctrine\Common\EventSubscriber;

trigger_deprecation('sylius/resource-bundle', '1.10', 'The "%s" class is deprecated, use %s instead. It will be removed in 2.0.', AbstractDoctrineSubscriber::class, AbstractDoctrineListener::class);

abstract class AbstractDoctrineSubscriber extends AbstractDoctrineListener implements EventSubscriber
{
}
