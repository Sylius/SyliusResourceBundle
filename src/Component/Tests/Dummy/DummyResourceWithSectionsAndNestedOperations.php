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

namespace Sylius\Component\Resource\Tests\Dummy;

use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;

#[Resource(
    alias: 'app.dummy',
    section: 'admin',
    operations: [
        new Index(),
        new Create(),
    ],
)]
#[Resource(
    alias: 'app.dummy',
    section: 'shop',
    operations: [
        new Index(),
        new Show(),
    ],
)]
final class DummyResourceWithSectionsAndNestedOperations
{
}
