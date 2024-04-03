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

namespace Sylius\Component\Resource\Translation;

use Sylius\Resource\Model\TranslatableInterface;

interface_exists(\Sylius\Resource\Translation\TranslatableEntityLocaleAssignerInterface::class);

if (false) {
    interface TranslatableEntityLocaleAssignerInterface
    {
        public function assignLocale(TranslatableInterface $translatableEntity): void;
    }
}
