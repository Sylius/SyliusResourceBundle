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

namespace Sylius\Component\Resource\Tests;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Resource\ResourceActions as NewResourceActions;

final class ResourceActionsTest extends TestCase
{
    private ResourceActions $actions;

    protected function setUp(): void
    {
        $this->actions = new ResourceActions();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ResourceActions::class, $this->actions);
    }

    public function testItIsAnAliasOfResourceActions(): void
    {
        $this->assertInstanceOf(NewResourceActions::class, $this->actions);
    }
}
