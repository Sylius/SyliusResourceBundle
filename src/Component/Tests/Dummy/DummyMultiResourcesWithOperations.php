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

use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;

#[AsResource(alias: 'app.order')]
#[Index]
#[Create]
#[AsResource(alias: 'app.cart')]
#[Index]
#[Show]
final class DummyMultiResourcesWithOperations
{
}
