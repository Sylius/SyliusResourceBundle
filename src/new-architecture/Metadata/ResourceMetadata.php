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

final class ResourceMetadata
{
    private ?Operations $operations;

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
        protected ?array $normalizationContext = null,
        protected ?array $denormalizationContext = null,
        protected ?array $validationContext = null,
        private ?string $class = null,
        private string|false|null $driver = null,
        private ?array $vars = null,
        ?array $operations = null,
    ) {
        $this->operations = null === $operations ? null : new Operations($operations);
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function withClass(string $class): self
    {
        $self = clone $this;
        $self->class = $class;

        return $self;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function withAlias(string $alias): self
    {
        $self = clone $this;
        $self->alias = $alias;

        return $self;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function withSection(string $section): self
    {
        $self = clone $this;
        $self->section = $section;

        return $self;
    }

    public function getFormType(): ?string
    {
        return $this->formType;
    }

    public function withFormType(string $formType): self
    {
        $self = clone $this;
        $self->formType = $formType;

        return $self;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function withName(string $name): self
    {
        $self = clone $this;
        $self->name = $name;

        return $self;
    }

    public function getPluralName(): ?string
    {
        return $this->pluralName;
    }

    public function withPluralName(string $pluralName): self
    {
        $self = clone $this;
        $self->pluralName = $pluralName;

        return $self;
    }

    public function getApplicationName(): ?string
    {
        return $this->applicationName;
    }

    public function withApplicationName(string $applicationName): self
    {
        $self = clone $this;
        $self->applicationName = $applicationName;

        return $self;
    }

    public function getTemplatesDir(): ?string
    {
        return $this->templatesDir;
    }

    public function withTemplatesDir(string $templatesDir): self
    {
        $self = clone $this;
        $self->templatesDir = $templatesDir;

        return $self;
    }

    public function getRoutePrefix(): ?string
    {
        return $this->routePrefix;
    }

    public function withRoutePrefix(string $routePrefix): self
    {
        $self = clone $this;
        $self->routePrefix = $routePrefix;

        return $self;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function withIdentifier(string $identifier): self
    {
        $self = clone $this;
        $self->identifier = $identifier;

        return $self;
    }

    public function hasOperation(string $name): bool
    {
        return $this->operations?->has($name) ?? false;
    }

    public function getOperation(string $name): Operation
    {
        if (null === $operations = $this->operations) {
            throw new \RuntimeException(sprintf('No Operations were found on resource %s"', $this->alias ?? ''));
        }

        return $operations->get($name);
    }

    public function getOperations(): ?Operations
    {
        return $this->operations;
    }

    public function withOperations(Operations $operations): self
    {
        $self = clone $this;
        $self->operations = $operations;

        return $self;
    }

    public function getRouteName(string $shortName): string
    {
        $section = $this->getSection();
        $sectionPrefix = $section ? $section . '_' : '';

        return sprintf(
            '%s_%s%s_%s',
            $this->getApplicationName() ?? '',
            $sectionPrefix,
            $this->getName() ?? '',
            $shortName,
        );
    }

    public function getNormalizationContext(): ?array
    {
        return $this->normalizationContext;
    }

    public function withNormalizationContext(?array $normalizationContext): self
    {
        $self = clone $this;
        $self->normalizationContext = $normalizationContext;

        return $self;
    }

    public function getDenormalizationContext(): ?array
    {
        return $this->denormalizationContext;
    }

    public function withDenormalizationContext(?array $denormalizationContext): self
    {
        $self = clone $this;
        $self->denormalizationContext = $denormalizationContext;

        return $self;
    }

    public function getValidationContext(): ?array
    {
        return $this->validationContext;
    }

    public function withValidationContext(?array $validationContext): self
    {
        $self = clone $this;
        $self->validationContext = $validationContext;

        return $self;
    }

    public function getDriver(): false|string|null
    {
        return $this->driver;
    }

    public function withDriver(false|string $driver): self
    {
        $self = clone $this;
        $self->driver = $driver;

        return $self;
    }

    public function getVars(): ?array
    {
        return $this->vars;
    }

    public function withVars(array $vars): self
    {
        $self = clone $this;
        $self->vars = $vars;

        return $self;
    }
}
