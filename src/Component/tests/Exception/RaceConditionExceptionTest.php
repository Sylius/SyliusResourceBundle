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

namespace Sylius\Resource\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Exception\RaceConditionException;
use Sylius\Resource\Exception\UpdateHandlingException;

final class RaceConditionExceptionTest extends TestCase
{
    public function testItExtendsAnUpdateHandlingException(): void
    {
        $exception = new RaceConditionException();
        $this->assertInstanceOf(UpdateHandlingException::class, $exception);
    }

    public function testItHasAMessage(): void
    {
        $exception = new RaceConditionException();
        $this->assertSame('Operated entity was previously modified.', $exception->getMessage());
    }

    public function testItHasAFlash(): void
    {
        $exception = new RaceConditionException();
        $this->assertSame('race_condition_error', $exception->getFlash());
    }

    public function testItHasAnApiResponseCode(): void
    {
        $exception = new RaceConditionException();
        $this->assertSame(409, $exception->getApiResponseCode());
    }
}
