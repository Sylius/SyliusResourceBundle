<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Tests\Dummy;

use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Section;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;

#[Resource(alias: 'app.dummy')]
#[Section(name: 'admin', routePrefix: '/admin', templatesDir: 'dummy')]
#[Index]
#[Create]
#[Update]
#[Show]
final class DummyResourceWithSections
{
}
