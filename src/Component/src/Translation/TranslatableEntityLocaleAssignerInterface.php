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

namespace Sylius\Resource\Translation;

use Sylius\Resource\Model\TranslatableInterface;

interface TranslatableEntityLocaleAssignerInterface
{
    public function assignLocale(TranslatableInterface $translatableEntity): void;
}

class_alias(TranslatableEntityLocaleAssignerInterface::class, \Sylius\Component\Resource\Translation\TranslatableEntityLocaleAssignerInterface::class);
