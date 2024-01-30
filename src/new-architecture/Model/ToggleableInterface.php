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

namespace Sylius\Resource\Model;

interface ToggleableInterface
{
    /**
     * Missing scalar typehint because it conflicts with AdvancedUserInterface.
     *
     * @return bool
     */
    public function isEnabled();

    /**
     * @param bool $enabled
     */
    public function setEnabled(?bool $enabled): void;

    public function enable(): void;

    public function disable(): void;
}

class_alias(ToggleableInterface::class, \Sylius\Component\Resource\Model\ToggleableInterface::class);
