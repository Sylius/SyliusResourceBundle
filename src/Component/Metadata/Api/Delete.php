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

namespace Sylius\Component\Resource\Metadata\Api;

use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;

/**
 * @experimental
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class Delete extends HttpOperation implements DeleteOperationInterface
{
    public function __construct(
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
        ?bool $read = null,
        ?bool $write = null,
        ?string $formType = null,
        ?array $formOptions = null,
        ?array $normalizationContext = null,
        ?array $denormalizationContext = null,
        ?string $redirectToRoute = null,
    ) {
        parent::__construct(
            methods: ['DELETE'],
            path: $path,
            routePrefix: $routePrefix,
            template: $template,
            shortName: $shortName ?? 'delete',
            name: $name,
            provider: $provider,
            processor: $processor,
            responder: $responder,
            repository: $repository,
            repositoryMethod: $repositoryMethod,
            read: $read,
            write: $write,
            formType: $formType,
            formOptions: $formOptions,
            normalizationContext: $normalizationContext,
            denormalizationContext: $denormalizationContext,
            redirectToRoute: $redirectToRoute,
        );
    }
}
