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

namespace Sylius\Component\Resource\Context\Option;

use Symfony\Component\HttpFoundation\Request;

final class RequestOption
{
    public function __construct(private Request $request)
    {
    }

    public function request(): Request
    {
        return $this->request;
    }
}
