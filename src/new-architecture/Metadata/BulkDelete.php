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

/**
 * @experimental
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class BulkDelete extends HttpOperation implements DeleteOperationInterface, BulkOperationInterface
{
    public function __construct(
        ?array $methods = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $template = null,
        ?string $shortName = null,
        ?string $name = null,
        string|callable|null $provider = null,
        string|callable|null $processor = null,
        string|callable|null $responder = null,
        string|callable|null $repository = null,
        ?array $repositoryArguments = null,
        ?string $repositoryMethod = null,
        ?bool $read = null,
        ?bool $write = null,
        ?string $formType = null,
        ?array $formOptions = null,
        ?string $eventShortName = null,
        ?string $redirectToRoute = null,
        ?array $redirectArguments = null,
        ?array $vars = null,
    ) {
        parent::__construct(
            methods: $methods ?? ['DELETE', 'POST'],
            path: $path,
            routePrefix: $routePrefix,
            template: $template,
            shortName: $shortName ?? 'bulk_delete',
            name: $name,
            provider: $provider,
            processor: $processor,
            responder: $responder,
            repository: $repository,
            repositoryMethod: $repositoryMethod,
            repositoryArguments: $repositoryArguments,
            read: $read,
            write: $write,
            formType: $formType,
            formOptions: $formOptions,
            eventShortName: $eventShortName,
            redirectToRoute: $redirectToRoute,
            redirectArguments: $redirectArguments,
            vars: $vars,
        );
    }
}
