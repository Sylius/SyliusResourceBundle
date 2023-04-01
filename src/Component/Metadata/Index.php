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
final class Index extends HttpOperation implements CollectionOperationInterface
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
        ?string $repositoryMethod = null,
        ?array $repositoryArguments = null,
        ?string $grid = null,
        ?bool $read = null,
        ?bool $write = null,
        ?bool $validate = null,
        ?bool $deserialize = null,
        ?bool $serialize = null,
        ?string $formType = null,
        ?array $formOptions = null,
        ?string $eventShortName = null,
        ?array $validationContext = null,
        ?string $redirectToRoute = null,
    ) {
        parent::__construct(
            methods: $methods ?? ['GET'],
            path: $path,
            routePrefix: $routePrefix,
            template: $template,
            shortName: $shortName ?? 'index',
            name: $name,
            provider: $provider,
            processor: $processor,
            responder: $responder,
            repository: $repository,
            repositoryMethod: $repositoryMethod,
            repositoryArguments: $repositoryArguments,
            grid: $grid,
            read: $read,
            write: $write,
            validate: $validate,
            deserialize: $deserialize,
            serialize: $serialize,
            formType: $formType,
            formOptions: $formOptions,
            validationContext: $validationContext,
            eventShortName: $eventShortName,
            redirectToRoute: $redirectToRoute,
        );
    }
}
