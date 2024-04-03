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

namespace spec\Sylius\Component\Resource;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Resource\ResourceActions as NewResourceActions;

final class ResourceActionsSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourceActions::class);
    }

    function it_is_an_alias_of_resource_actions(): void
    {
        $this->shouldHaveType(NewResourceActions::class);
    }
}
