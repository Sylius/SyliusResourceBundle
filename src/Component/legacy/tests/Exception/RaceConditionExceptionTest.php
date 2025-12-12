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
use Sylius\Component\Resource\Exception\RaceConditionException;
use Sylius\Resource\Exception\RaceConditionException as NewRaceConditionException;

final class RaceConditionExceptionTest extends TestCase
{
    public function testItShouldBeAnAliasOfRaceConditionException(): void
    {
        $this->assertInstanceOf(NewRaceConditionException::class, new RaceConditionException());
    }
}
