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

namespace Sylius\Bundle\ResourceBundle\Tests\Validator\Constraints;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Validator\Constraints\UniqueWithinCollectionConstraint;

final class UniqueWithinCollectionConstraintTest extends TestCase
{
    public function testUsesDefaultValues(): void
    {
        $constraint = new UniqueWithinCollectionConstraint();

        $this->assertSame('This code must be unique within this collection.', $constraint->message);
        $this->assertSame('code', $constraint->attributePath);
    }

    public function testAcceptsNamedArguments(): void
    {
        $constraint = new UniqueWithinCollectionConstraint(message: 'Custom message', attributePath: 'name');

        $this->assertSame('Custom message', $constraint->message);
        $this->assertSame('name', $constraint->attributePath);
    }

    /**
     * @group legacy
     */
    public function testSupportsLegacyArrayOptionsAndTriggersDeprecation(): void
    {
        $deprecations = [];
        set_error_handler(static function (int $type, string $message) use (&$deprecations): bool {
            $deprecations[] = $message;

            return true;
        }, \E_USER_DEPRECATED);

        try {
            $constraint = new UniqueWithinCollectionConstraint([
                'message' => 'Legacy message',
                'attributePath' => 'name',
            ]);
        } finally {
            restore_error_handler();
        }

        $this->assertSame('Legacy message', $constraint->message);
        $this->assertSame('name', $constraint->attributePath);
        $this->assertCount(1, $deprecations);
        $this->assertStringContainsString('Passing an array of options', $deprecations[0]);
    }
}
