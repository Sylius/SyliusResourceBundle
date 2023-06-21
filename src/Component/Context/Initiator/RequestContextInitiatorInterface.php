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

namespace Sylius\Component\Resource\Context\Initiator;

use Sylius\Component\Resource\Context\Context;
use Symfony\Component\HttpFoundation\Request;

interface RequestContextInitiatorInterface
{
    public function initializeContext(Request $request): Context;
}
