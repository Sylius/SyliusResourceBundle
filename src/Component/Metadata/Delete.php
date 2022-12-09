<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Metadata;

/**
 * @experimental
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class Delete extends HttpOperation implements DeleteOperationInterface
{
    public function __construct(
        ?array $methods = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $name = null,
        ?string $template = null,
    ) {
        parent::__construct(
            methods: $methods ?? ['DELETE'],
            path: $path,
            routePrefix: $routePrefix,
            name: $name ?? 'delete',
            template: $template,
        );
    }
}
