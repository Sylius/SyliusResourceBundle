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
use Sylius\Component\Resource\Exception\DeleteHandlingException;
use Sylius\Resource\Exception\DeleteHandlingException as NewDeleteHandlingException;

final class DeleteHandlingExceptionTest extends TestCase
{
    public function testItShouldBeAnAliasOfDeleteHandlingException(): void
    {
        $this->assertInstanceOf(NewDeleteHandlingException::class, new DeleteHandlingException());
    }
}
