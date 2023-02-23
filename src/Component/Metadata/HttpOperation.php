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
        string|callable|null $responder = null,
        string|callable|null $repository = null,
        ?string $repositoryMethod = null,
        ?string $grid = null,
        ?bool $read = null,
        ?bool $write = null,
        ?bool $validate = null,
        ?string $formType = null,
        ?array $formOptions = null,
        ?string $stateMachineComponent = null,
        ?string $stateMachineTransition = null,
        ?string $stateMachineGraph = null,
        protected ?string $redirectToRoute = null,
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
            grid: $grid,
            read: $read,
            write: $write,
            validate: $validate,
            formType: $formType,
            formOptions: $formOptions,
            stateMachineComponent: $stateMachineComponent,
            stateMachineTransition: $stateMachineTransition,
            stateMachineGraph: $stateMachineGraph,
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

    public function withRoutePrefix(?string $routePrefix): self
    {
        $self = clone $this;
        $self->routePrefix = $routePrefix;

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
}
