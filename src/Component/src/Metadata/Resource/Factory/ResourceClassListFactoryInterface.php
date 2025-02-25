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

namespace Sylius\Resource\Metadata\Resource\Factory;

use Sylius\Resource\Metadata\Resource\ResourceClassList;

/**
 * Creates a resource class list value object.
 *
 * @experimental
 */
interface ResourceClassListFactoryInterface
{
    /**
     * Creates the resource class list.
     */
    public function create(): ResourceClassList;
}
