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

namespace Sylius\Component\Resource\Factory;

use Sylius\Resource\Factory\FactoryInterface;

interface_exists(\Sylius\Resource\Factory\TranslatableFactoryInterface::class);

if (false) {
    /**
     * {@inheritDoc}
     */
    interface TranslatableFactoryInterface extends \Sylius\Resource\Factory\TranslatableFactoryInterface
    {
        /**
         * {@inheritDoc}
         */
        public function createNew();
    }
}


