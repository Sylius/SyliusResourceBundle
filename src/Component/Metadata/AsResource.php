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

namespace Sylius\Component\Resource\Metadata;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class AsResource
{
    public function __construct(
        ?string $alias = null,
        ?string $section = null,
        ?string $formType = null,
        ?string $templatesDir = null,
        ?string $routePrefix = null,
        ?string $name = null,
        ?string $pluralName = null,
        ?string $applicationName = null,
        ?string $identifier = null,
        ?array $normalizationContext = null,
        ?array $denormalizationContext = null,
        ?array $validationContext = null,
        ?string $class = null,
        string|false|null $driver = null,
        ?array $vars = null,
        ?array $operations = null,
    ) {
        return new ResourceMetadata(
            alias: $alias,
            section: $section,
            formType: $formType,
            templatesDir: $templatesDir,
            routePrefix: $routePrefix,
            name: $name,
            pluralName: $pluralName,
            applicationName: $applicationName,
            identifier: $identifier,
            normalizationContext: $normalizationContext,
            denormalizationContext: $denormalizationContext,
            validationContext: $validationContext,
            class: $class,
            driver: $driver,
            vars: $vars,
            operations: $operations,
        );
    }
}
