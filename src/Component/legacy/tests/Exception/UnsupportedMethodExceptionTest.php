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

namespace Sylius\Component\Resource\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Exception\UnsupportedMethodException;
use Sylius\Resource\Exception\UnsupportedMethodException as NewUnsupportedMethodException;

final class UnsupportedMethodExceptionTest extends TestCase
{
    public function testItShouldBeAnAliasOfUnsupportedMethodException(): void
    {
        $this->assertInstanceOf(NewUnsupportedMethodException::class, new UnsupportedMethodException('methodName'));
    }
}
