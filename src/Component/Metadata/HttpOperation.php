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
    /** @var string|callable|null */
    protected $twigContextFactory;

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
        ?array $normalizationContext = null,
        ?array $denormalizationContext = null,
        ?array $validationContext = null,
        ?string $eventShortName = null,
        string|callable|null $twigContextFactory = null,
        protected ?string $redirectToRoute = null,
        protected ?array $redirectArguments = null,
    ) {
        parent::__construct(
            template: $template,
            shortName: $shortName,
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
            normalizationContext: $normalizationContext,
            denormalizationContext: $denormalizationContext,
            validationContext: $validationContext,
            eventShortName: $eventShortName,
        );

        $this->twigContextFactory = $twigContextFactory;
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

    public function withRoutePrefix(?string $routePrefix): self
    {
        $self = clone $this;
        $self->routePrefix = $routePrefix;

        return $self;
    }

    public function getTwigContextFactory(): callable|string|null
    {
        return $this->twigContextFactory;
    }

    public function withTwigContextFactory(callable|string|null $twigContextFactory): self
    {
        $self = clone $this;
        $self->twigContextFactory = $twigContextFactory;

        return $self;
    }

    public function getRedirectToRoute(): ?string
    {
        return $this->redirectToRoute;
    }

    public function withRedirectToRoute(string $redirectToRoute): self
    {
        $self = clone $this;
        $self->redirectToRoute = $redirectToRoute;

        return $self;
    }

    public function getRedirectArguments(): ?array
    {
        return $this->redirectArguments;
    }

    public function withRedirectArguments(array $redirectArguments): self
    {
        $self = clone $this;
        $self->redirectArguments = $redirectArguments;

        return $self;
    }
}
