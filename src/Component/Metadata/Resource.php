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
        private ?string $alias = null,
        private ?string $section = null,
        private ?string $routePrefix = null,
        private ?string $templatesDir = null,
        ?array $operations = null,
    ) {
        $this->operations = null === $operations ? null : new Operations($operations);
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

    public function getRoutePrefix(): ?string
    {
        return $this->routePrefix;
    }

    public function withRoutePrefix(string $routePrefix): self
    {
        $self = clone $this;
        $this->routePrefix = $routePrefix;

        return $self;
    }

    public function getTemplatesDir(): ?string
    {
        return $this->templatesDir;
    }

    public function withTemplatesDir(string $templatesDir): self
    {
        $self = clone $this;
        $this->templatesDir = $templatesDir;

        return $self;
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
