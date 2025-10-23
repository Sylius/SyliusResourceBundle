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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Driver\Exception;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Exception\InvalidDriverException;
final class InvalidDriverExceptionTest extends TestCase
{
    private InvalidDriverException $invalidDriverException;
    protected function setUp(): void
    {
        $this->invalidDriverException = new InvalidDriverException('driver', 'className');
    }

    function testHasAMessage(): void
    {
        $this->assertSame('Driver "driver" is not supported by className.', $this->invalidDriverException->getMessage());
    }
}
