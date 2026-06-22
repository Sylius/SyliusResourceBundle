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
use Sylius\Bundle\ResourceBundle\Validator\Constraints\Disabled;

final class DisabledTest extends TestCase
{
    public function testUsesDefaultMessage(): void
    {
        $constraint = new Disabled();

        $this->assertSame('sylius.resource.not_disabled', $constraint->message);
    }

    public function testAcceptsNamedArguments(): void
    {
        $constraint = new Disabled(message: 'Custom message');

        $this->assertSame('Custom message', $constraint->message);
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
            $constraint = new Disabled(['message' => 'Legacy message']);
        } finally {
            restore_error_handler();
        }

        $this->assertSame('Legacy message', $constraint->message);
        $this->assertCount(1, $deprecations);
        $this->assertStringContainsString('Passing an array of options', $deprecations[0]);
    }
}
