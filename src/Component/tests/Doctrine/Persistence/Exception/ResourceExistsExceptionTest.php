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

namespace Sylius\Resource\Tests\Doctrine\Persistence\Exception;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Doctrine\Persistence\Exception\ExceptionInterface;
use Sylius\Resource\Doctrine\Persistence\Exception\ResourceExistsException;

final class ResourceExistsExceptionTest extends TestCase
{
    use ProphecyTrait;

    public function testItExtendsException(): void
    {
        $exception = new ResourceExistsException();
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testItExtendsRuntimeException(): void
    {
        $exception = new ResourceExistsException();
        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testItImplementsExceptionInterface(): void
    {
        $exception = new ResourceExistsException();
        $this->assertInstanceOf(ExceptionInterface::class, $exception);
    }

    public function testItHasAMessage(): void
    {
        $exception = new ResourceExistsException();
        $this->assertEquals('Given resource already exists in the repository.', $exception->getMessage());
    }
}
