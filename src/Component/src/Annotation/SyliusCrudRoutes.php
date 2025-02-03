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

namespace Sylius\Resource\Annotation;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class SyliusCrudRoutes
{
    public function __construct(
        public ?string $alias = null,
        public ?string $path = null,
        public ?string $identifier = null,
        public ?array $criteria = null,
        public ?bool $filterable = null,
        public ?string $form = null,
        public ?string $serializationVersion = null,
        public ?string $section = null,
        public ?string $redirect = null,
        public ?string $templates = null,
        public ?string $grid = null,
        public ?bool $permission = null,
        public ?array $except = null,
        public ?array $only = null,
        public ?array $vars = null,
    ) {
    }
}

class_alias(SyliusCrudRoutes::class, \Sylius\Component\Resource\Annotation\SyliusCrudRoutes::class);
