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

namespace Sylius\Resource\Tests\Humanizer;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Humanizer\StringHumanizer;

final class StringHumanizerTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $humanizer = new StringHumanizer();
        $this->assertInstanceOf(StringHumanizer::class, $humanizer);
    }

    public function testItHumanizesAString(): void
    {
        $humanizer = new StringHumanizer();

        $this->assertSame('admin user', $humanizer::humanize('admin_user'));
        $this->assertSame('admin user', $humanizer::humanize('Admin_user'));
        $this->assertSame('admin user', $humanizer::humanize('AdminUser'));
    }
}
