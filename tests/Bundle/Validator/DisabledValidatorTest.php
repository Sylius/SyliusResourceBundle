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

use Sylius\Bundle\ResourceBundle\Validator\Constraints\Disabled;
use Sylius\Bundle\ResourceBundle\Validator\DisabledValidator;
use Sylius\Resource\Model\ToggleableInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<DisabledValidator>
 */
final class DisabledValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): DisabledValidator
    {
        return new DisabledValidator();
    }

    public function testDoesNotApplyToNullValues(): void
    {
        $constraint = new Disabled();

        $this->validator->validate(null, $constraint);

        $this->assertNoViolation();
    }

    public function testThrowsExceptionIfSubjectDoesNotImplementToggleableInterface(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            '"%s" validates "%s" instances only',
            DisabledValidator::class,
            ToggleableInterface::class,
        ));

        $constraint = new Disabled();

        $this->validator->validate(new \stdClass(), $constraint);
    }

    public function testAddsViolationWhenResourceIsEnabled(): void
    {
        $subject = $this->createMock(ToggleableInterface::class);
        $subject
            ->method('isEnabled')
            ->willReturn(true);

        $constraint = new Disabled();

        $this->validator->validate($subject, $constraint);

        $this->buildViolation($constraint->message)
            ->assertRaised();
    }

    public function testDoesNotAddViolationWhenResourceIsDisabled(): void
    {
        $subject = $this->createMock(ToggleableInterface::class);
        $subject
            ->method('isEnabled')
            ->willReturn(false);

        $constraint = new Disabled();

        $this->validator->validate($subject, $constraint);

        $this->assertNoViolation();
    }
}
