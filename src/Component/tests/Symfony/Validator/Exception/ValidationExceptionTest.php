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

namespace Sylius\Resource\Tests\Symfony\Validator\Exception;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;

final class ValidationExceptionTest extends TestCase
{
    public function testItTransformsExceptionIntoAString(): void
    {
        $firstViolation = $this->createMock(ConstraintViolationInterface::class);
        $secondViolation = $this->createMock(ConstraintViolationInterface::class);

        $firstViolation->method('getPropertyPath')->willReturn('name');
        $firstViolation->method('getMessage')->willReturn('This value should not be blank.');

        $secondViolation->method('getPropertyPath')->willReturn('email');
        $secondViolation->method('getMessage')->willReturn('This value should not be blank.');

        $exception = new ValidationException(new ConstraintViolationList([
            $firstViolation,
            $secondViolation,
        ]));

        $this->assertSame("name: This value should not be blank.\nemail: This value should not be blank.", (string) $exception);
    }

    public function testItCanBeConstructedWithAMessage(): void
    {
        $exception = new ValidationException(new ConstraintViolationList([]), 'You should not pass!');

        $this->assertSame('You should not pass!', $exception->getMessage());
    }

    public function testItCanBeConstructedWithACode(): void
    {
        $exception = new ValidationException(new ConstraintViolationList([]), '', 42);

        $this->assertSame(42, $exception->getCode());
    }

    public function testItCanBeConstructedWithAPreviousException(): void
    {
        $previous = new \Exception();

        $exception = new ValidationException(new ConstraintViolationList([]), '', 0, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }
}
