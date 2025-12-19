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

namespace Sylius\Component\Resource\Tests\Repository\Exception;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Repository\Exception\ExistingResourceException;
use Sylius\Resource\Doctrine\Persistence\Exception\ResourceExistsException;

final class ExistingResourceExceptionTest extends TestCase
{
    private ExistingResourceException $exception;

    protected function setUp(): void
    {
        $this->exception = new ExistingResourceException();
    }

    public function testItExtendsException(): void
    {
        $this->assertInstanceOf(\Exception::class, $this->exception);
    }

    public function testItShouldBeAnAliasOfResourceExistsException(): void
    {
        $this->assertInstanceOf(ResourceExistsException::class, $this->exception);
    }
}
