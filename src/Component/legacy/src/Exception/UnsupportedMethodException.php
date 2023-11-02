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

namespace Sylius\Component\Resource\Exception;

class_exists(\Sylius\Resource\Exception\UnsupportedMethodException::class);

if (false) {
    class UnsupportedMethodException extends \Sylius\Resource\Exception\UnsupportedMethodException
    {
    }
}
