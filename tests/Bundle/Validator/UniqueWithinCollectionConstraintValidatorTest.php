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

namespace Sylius\Bundle\ResourceBundle\Tests\Validator;

use Sylius\Bundle\ResourceBundle\Validator\Constraints\UniqueWithinCollectionConstraint;
use Sylius\Bundle\ResourceBundle\Validator\UniqueWithinCollectionConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<UniqueWithinCollectionConstraintValidator>
 */
final class UniqueWithinCollectionConstraintValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): UniqueWithinCollectionConstraintValidator
    {
        return new UniqueWithinCollectionConstraintValidator();
    }

    public function testDoesNotAddViolationWhenCollectionIsEmpty(): void
    {
        $constraint = new UniqueWithinCollectionConstraint();

        $this->validator->validate([], $constraint);

        $this->assertNoViolation();
    }

    public function testDoesNotAddViolationWhenAllAttributesAreUnique(): void
    {
        $collection = [
            $this->createEntityWithCode('CODE_1'),
            $this->createEntityWithCode('CODE_2'),
            $this->createEntityWithCode('CODE_3'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();

        $this->validator->validate($collection, $constraint);

        $this->assertNoViolation();
    }

    public function testDoesNotAddViolationWhenAttributeIsNull(): void
    {
        $collection = [
            $this->createEntityWithCode(null),
            $this->createEntityWithCode(null),
            $this->createEntityWithCode('CODE_1'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();

        $this->validator->validate($collection, $constraint);

        $this->assertNoViolation();
    }

    public function testAddsViolationWhenTwoAttributesAreDuplicated(): void
    {
        $collection = [
            $this->createEntityWithCode('CODE_1'),
            $this->createEntityWithCode('DUPLICATE'),
            $this->createEntityWithCode('DUPLICATE'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();

        $this->setPropertyPath('');
        $this->validator->validate($collection, $constraint);

        $this->buildViolation($constraint->message)
            ->atPath('[2].code')
            ->buildNextViolation($constraint->message)
            ->atPath('[1].code')
            ->assertRaised();
    }

    public function testAddsViolationForFirstAndSecondOccurrenceOfDuplicate(): void
    {
        $collection = [
            $this->createEntityWithCode('DUPLICATE'),
            $this->createEntityWithCode('DUPLICATE'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();

        $this->setPropertyPath('');
        $this->validator->validate($collection, $constraint);

        $this->buildViolation($constraint->message)
            ->atPath('[1].code')
            ->buildNextViolation($constraint->message)
            ->atPath('[0].code')
            ->assertRaised();
    }

    public function testAddsViolationOnlyOnceForFirstOccurrenceWhenThreeOrMoreDuplicates(): void
    {
        $collection = [
            $this->createEntityWithCode('DUPLICATE'),
            $this->createEntityWithCode('DUPLICATE'),
            $this->createEntityWithCode('DUPLICATE'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();

        $this->setPropertyPath('');
        $this->validator->validate($collection, $constraint);

        $this->buildViolation($constraint->message)
            ->atPath('[1].code')
            ->buildNextViolation($constraint->message)
            ->atPath('[0].code')
            ->buildNextViolation($constraint->message)
            ->atPath('[2].code')
            ->assertRaised();
    }

    public function testHandlesMultipleDifferentDuplicates(): void
    {
        $collection = [
            $this->createEntityWithCode('DUP_1'),
            $this->createEntityWithCode('DUP_1'),
            $this->createEntityWithCode('UNIQUE'),
            $this->createEntityWithCode('DUP_2'),
            $this->createEntityWithCode('DUP_2'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();

        $this->setPropertyPath('');
        $this->validator->validate($collection, $constraint);

        $this->buildViolation($constraint->message)
            ->atPath('[1].code')
            ->buildNextViolation($constraint->message)
            ->atPath('[0].code')
            ->buildNextViolation($constraint->message)
            ->atPath('[4].code')
            ->buildNextViolation($constraint->message)
            ->atPath('[3].code')
            ->assertRaised();
    }

    public function testUsesCustomAttributePath(): void
    {
        $collection = [
            $this->createEntityWithCustomAttribute('name', 'John'),
            $this->createEntityWithCustomAttribute('name', 'John'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();
        $constraint->attributePath = 'name';

        $this->setPropertyPath('');
        $this->validator->validate($collection, $constraint);

        $this->buildViolation($constraint->message)
            ->atPath('[1].name')
            ->buildNextViolation($constraint->message)
            ->atPath('[0].name')
            ->assertRaised();
    }

    public function testUsesCustomMessage(): void
    {
        $collection = [
            $this->createEntityWithCode('DUPLICATE'),
            $this->createEntityWithCode('DUPLICATE'),
        ];

        $constraint = new UniqueWithinCollectionConstraint();
        $constraint->message = 'Custom error message';

        $this->setPropertyPath('');
        $this->validator->validate($collection, $constraint);

        $this->buildViolation('Custom error message')
            ->atPath('[1].code')
            ->buildNextViolation('Custom error message')
            ->atPath('[0].code')
            ->assertRaised();
    }

    private function createEntityWithCode(?string $code): object
    {
        return new class($code) {
            public function __construct(
                private readonly ?string $code,
            ) {
            }

            public function getCode(): ?string
            {
                return $this->code;
            }
        };
    }

    private function createEntityWithCustomAttribute(string $attributeName, mixed $value): object
    {
        return new class($attributeName, $value) {
            public function __construct(
                private readonly string $attributeName,
                private readonly mixed $value,
            ) {
            }

            public function __get(string $name): mixed
            {
                if ($name === $this->attributeName) {
                    return $this->value;
                }

                throw new \Exception("Property {$name} does not exist");
            }

            public function __isset(string $name): bool
            {
                return $name === $this->attributeName;
            }
        };
    }
}
