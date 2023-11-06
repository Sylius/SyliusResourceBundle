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

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class AsResource
{
    public function __construct(
        private ?string $alias = null,
        private ?string $section = null,
        private ?string $formType = null,
        private ?string $templatesDir = null,
        private ?string $routePrefix = null,
        private ?string $name = null,
        private ?string $pluralName = null,
        private ?string $applicationName = null,
        private ?string $identifier = null,
        private ?array $normalizationContext = null,
        private ?array $denormalizationContext = null,
        private ?array $validationContext = null,
        private ?string $class = null,
        private string|false|null $driver = null,
        private ?array $vars = null,
        private ?array $operations = null,
    ) {
    }

    public function toMetadata(): ResourceMetadata
    {
        return new ResourceMetadata(
            alias: $this->alias,
            section: $this->section,
            formType: $this->formType,
            templatesDir: $this->templatesDir,
            routePrefix: $this->routePrefix,
            name: $this->name,
            pluralName: $this->pluralName,
            applicationName: $this->applicationName,
            identifier: $this->identifier,
            normalizationContext: $this->normalizationContext,
            denormalizationContext: $this->denormalizationContext,
            validationContext: $this->validationContext,
            class: $this->class,
            driver: $this->driver,
            vars: $this->vars,
            operations: $this->operations,
        );
    }
}
