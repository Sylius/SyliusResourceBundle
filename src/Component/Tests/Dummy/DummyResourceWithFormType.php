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
use Sylius\Component\Resource\Metadata\AsResource;
use Sylius\Component\Resource\Metadata\Update;

#[AsResource(alias: 'app.dummy', formType: 'App\Form\DummyType')]
#[Create]
#[Update]
final class DummyResourceWithFormType
{
}
