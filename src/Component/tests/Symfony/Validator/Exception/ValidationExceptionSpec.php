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

namespace Tests\Sylius\Resource\Symfony\Validator\Exception;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;

final class ValidationExceptionTest extends TestCase
{
    private ValidationException $validationException;

    protected function setUp(): void
    {
        $firstViolation = $this->createMock(ConstraintViolationInterface::class);
        $secondViolation = $this->createMock(ConstraintViolationInterface::class);

        $violationList = new ConstraintViolationList([
            $firstViolation,
            $secondViolation,
        ]);

        $this->validationException = new ValidationException($violationList);
    }

    public function testItTransformsExceptionIntoAString(): void
    {
        $firstViolation = $this->createMock(ConstraintViolationInterface::class);
        $secondViolation = $this->createMock(ConstraintViolationInterface::class);

        $firstViolation->method('getPropertyPath')->willReturn('name');
        $firstViolation->method('getMessage')->willReturn('This value should not be blank.');

        $secondViolation->method('getPropertyPath')->willReturn('email');
        $secondViolation->method('getMessage')->willReturn('This value should not be blank.');

        $violationList = new ConstraintViolationList([
            $firstViolation,
            $secondViolation,
        ]);

        $this->validationException = new ValidationException($violationList);

        $this->assertEquals(
            "name: This value should not be blank.\nemail: This value should not be blank.",
            $this->validationException->__toString(),
        );
    }

    public function testItCanBeConstructedWithAMessage(): void
    {
        $this->validationException = new ValidationException(new ConstraintViolationList([]), 'You should not pass!');

        $this->assertEquals('You should not pass!', $this->validationException->getMessage());
    }

    public function testItCanBeConstructedWithACode(): void
    {
        $this->validationException = new ValidationException(new ConstraintViolationList([]), '', 42);

        $this->assertEquals(42, $this->validationException->getCode());
    }

    public function testItCanBeConstructedWithAPreviousException(): void
    {
        $previous = new \Exception();

        $this->validationException = new ValidationException(new ConstraintViolationList([]), '', 0, $previous);

        $this->assertSame($previous, $this->validationException->getPrevious());
    }
}
