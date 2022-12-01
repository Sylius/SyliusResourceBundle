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
final class Create extends Operation implements CreateOperationInterface
{
    public function __construct(
        ?string $name = null,
        ?array $methods = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $controller = null,
        ?string $template = null,
    ) {
        parent::__construct(
            name: $name ?? 'create',
            methods: $methods ?? ['GET', 'POST'],
            path: $path,
            routePrefix: $routePrefix,
            controller: $controller,
            template: $template,
        );
    }
}
