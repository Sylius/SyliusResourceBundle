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

namespace Sylius\Bundle\ResourceBundle\Controller;

interface AuthorizationCheckerInterface
{
    /**
     * Checks if user is authorized based on the current request configuration and specific permission.
     *
     * Sample permissions:
     *
     * - create
     * - show
     * - delete
     * - custom_action
     */
    public function isGranted(RequestConfiguration $configuration, string $permission): bool;
}
