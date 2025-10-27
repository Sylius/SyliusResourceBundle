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
use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Exception\UnknownDriverException;

final class UnknownDriverExceptionTest extends TestCase
{
    private UnknownDriverException $unknownDriverException;

    protected function setUp(): void
    {
        $this->unknownDriverException = new UnknownDriverException('driver');
    }

    public function testHasAMessage(): void
    {
        $this->assertSame('Unknown driver "driver".', $this->unknownDriverException->getMessage());
    }
}
