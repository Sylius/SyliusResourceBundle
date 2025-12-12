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
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Resource\Exception\UnexpectedTypeException as NewUnexpectedTypeException;

final class UnexpectedTypeExceptionTest extends TestCase
{
    public function testItShouldBeAnAliasOfUnexpectedTypeException(): void
    {
        $this->assertInstanceOf(NewUnexpectedTypeException::class, new UnexpectedTypeException('stringValue', '\ExpectedType'));
    }
}
