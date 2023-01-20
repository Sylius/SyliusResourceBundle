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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Component\Resource\Symfony\Request\RequestConfiguration;

/**
 * This authorization checker always returns true. Useful if you don't want to have authorization checks at all.
 */
final class DisabledAuthorizationChecker implements AuthorizationCheckerInterface
{
    public function isGranted(RequestConfiguration $configuration, string $permission): bool
    {
        return true;
    }
}
