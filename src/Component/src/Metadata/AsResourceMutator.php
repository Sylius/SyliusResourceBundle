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

namespace Sylius\Resource\Metadata;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsResourceMutator
{
    /**
     * @param class-string $resourceClass
     */
    public function __construct(
        public readonly string $resourceClass,
    ) {
    }
}
