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

use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Metadata\AsResource;

#[ORM\Entity]
#[AsResource]
final class DummyResource
{
}
