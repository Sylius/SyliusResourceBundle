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

namespace spec\Sylius\Resource\Humanizer;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Humanizer\StringHumanizer;

final class StringHumanizerSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(StringHumanizer::class);
    }

    function it_humanizes_a_string(): void
    {
        $this::humanize('admin_user')->shouldReturn('admin user');
        $this::humanize('Admin_user')->shouldReturn('admin user');
        $this::humanize('AdminUser')->shouldReturn('admin user');
    }
}
