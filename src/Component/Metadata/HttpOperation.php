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
class HttpOperation extends Operation
{
    public function __construct(
        protected ?array $methods = null,
        protected ?string $path = null,
        protected ?string $routeName = null,
        protected ?string $routePrefix = null,
        ?string $template = null,
        ?string $shortName = null,
        ?string $name = null,
        string|callable|null $provider = null,
        string|callable|null $processor = null,
    ) {
        parent::__construct(
            template: $template,
            shortName: $shortName,
            name: $name,
            provider: $provider,
            processor: $processor,
        );
    }

    public function getMethods(): ?array
    {
        return $this->methods;
    }

    public function withMethods(array $methods): self
    {
        $self = clone $this;
        $self->methods = $methods;

        return $self;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function withPath(string $path): self
    {
        $self = clone $this;
        $self->path = $path;

        return $self;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function withRouteName(string $routeName): self
    {
        $self = clone $this;
        $self->routeName = $routeName;

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
}
