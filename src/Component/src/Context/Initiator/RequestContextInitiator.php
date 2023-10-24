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

namespace Sylius\Resource\Context\Initiator;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\HttpFoundation\Request;

final class RequestContextInitiator implements RequestContextInitiatorInterface
{
    public function initializeContext(Request $request): Context
    {
        return new Context(new RequestOption($request));
    }
}
