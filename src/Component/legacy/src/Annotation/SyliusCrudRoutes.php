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

namespace Sylius\Component\Resource\Annotation;

class_exists(\Sylius\Resource\Annotation\SyliusCrudRoutes::class);

if (false) {
    #[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
    final class SyliusCrudRoutes extends \Sylius\Resource\Annotation\SyliusCrudRoutes
    {
    }
}
