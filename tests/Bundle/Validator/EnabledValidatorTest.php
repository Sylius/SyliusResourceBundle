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

use Sylius\Bundle\ResourceBundle\Validator\Constraints\Enabled;
use Sylius\Bundle\ResourceBundle\Validator\EnabledValidator;
use Sylius\Resource\Model\ToggleableInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<EnabledValidator>
 */
final class EnabledValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): EnabledValidator
    {
        return new EnabledValidator();
    }

    public function testDoesNotApplyToNullValues(): void
    {
        $constraint = new Enabled();

        $this->validator->validate(null, $constraint);

        $this->assertNoViolation();
    }

    public function testThrowsExceptionIfSubjectDoesNotImplementToggleableInterface(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            '"%s" validates "%s" instances only',
            EnabledValidator::class,
            ToggleableInterface::class,
        ));

        $constraint = new Enabled();

        $this->validator->validate(new \stdClass(), $constraint);
    }

    public function testAddsViolationWhenResourceIsDisabled(): void
    {
        $subject = $this->createMock(ToggleableInterface::class);
        $subject
            ->method('isEnabled')
            ->willReturn(false);

        $constraint = new Enabled();

        $this->validator->validate($subject, $constraint);

        $this->buildViolation($constraint->message)
            ->assertRaised();
    }

    public function testDoesNotAddViolationWhenResourceIsEnabled(): void
    {
        $subject = $this->createMock(ToggleableInterface::class);
        $subject
            ->method('isEnabled')
            ->willReturn(true);

        $constraint = new Enabled();

        $this->validator->validate($subject, $constraint);

        $this->assertNoViolation();
    }
}
