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

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class Resource
{
    private ?Operations $operations;

    public function __construct(
        private string $alias,
        private ?string $section = null,
        private ?string $formType = null,
        private ?string $templatesDir = null,
        private ?string $name = null,
        private ?string $applicationName = null,
        ?array $operations = null,
    ) {
        $this->operations = null === $operations ? null : new Operations($operations);
    }

    public function getAlias(): string
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

    public function hasOperation(string $name): bool
    {
        return $this->operations?->has($name) ?? false;
    }

    public function getOperation(string $name): Operation
    {
        if (null === $operations = $this->operations) {
            throw new \RuntimeException(sprintf('No Operations were found on resource %s"', $this->alias));
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
}
