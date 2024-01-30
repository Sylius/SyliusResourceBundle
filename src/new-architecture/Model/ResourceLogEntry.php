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

namespace Sylius\Resource\Model;

use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

abstract class ResourceLogEntry extends AbstractLogEntry implements ResourceInterface
{
}

class_alias(ResourceLogEntry::class, \Sylius\Component\Resource\Model\ResourceLogEntry::class);
