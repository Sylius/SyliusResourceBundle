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
final class Update extends HttpOperation implements UpdateOperationInterface
{
    public function __construct(
        ?array $methods = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $name = null,
        ?string $template = null,
    ) {
        parent::__construct(
            methods: $methods ?? ['GET', 'PUT'],
            path: $path,
            routePrefix: $routePrefix,
            name: $name ?? 'update',
            template: $template,
        );
    }
}