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

namespace Sylius\Resource;

final class ResourceActions
{
    public const SHOW = 'show';

    public const INDEX = 'index';

    public const CREATE = 'create';

    public const UPDATE = 'update';

    public const DELETE = 'delete';

    public const BULK_DELETE = 'bulk_delete';

    private function __construct()
    {
    }
}

class_alias(ResourceActions::class, \Sylius\Component\Resource\ResourceActions::class);
