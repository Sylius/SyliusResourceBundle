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

class Operation
{
    public function __construct(
        protected ?string $name = null,
        protected ?array $methods = null,
        protected ?string $path = null,
        protected ?string $routePrefix = null,
        protected ?string $controller = null,
        protected ?string $template = null,
        protected ?array $repository = null,
        protected ?array $criteria = null,
        protected ?array $serializationGroups = null,
        protected ?string $serializationVersion = null,
        protected ?array $requirements = null,
        protected ?array $options = null,
        protected ?string $host = null,
        protected ?array $schemes = null,
        protected ?int $priority = null,
        protected ?array $vars = null,
        protected string | array | bool | null $form = null,
        protected string | array | bool | null $factory = null,
        protected ?string $section = null,
        protected ?bool $permission = null,
        protected ?string $grid = null,
        protected ?bool $csrfProtection = null,
        protected string | array | null $redirect = null,
        protected ?array $stateMachine = null,
        protected ?string $event = null,
        protected ?bool $returnContent = null,
        protected ?string $resource = null,
        protected ?string $provider = null,
        protected ?string $processor = null,
        protected ?bool $read = null,
        protected ?bool $validate = null,
        protected ?bool $write = null,
        protected ?bool $respond = null,
        protected ?string $input = null,
    ) {
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

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function withController(string $controller): self
    {
        $self = clone $this;
        $self->controller = $controller;

        return $self;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function withTemplate(string $template): self
    {
        $self = clone $this;
        $self->template = $template;

        return $self;
    }

    public function getRepository(): ?array
    {
        return $this->repository;
    }

    public function withRepository(array $repository): self
    {
        $self = clone $this;
        $self->repository = $repository;

        return $self;
    }

    public function getCriteria(): ?array
    {
        return $this->criteria;
    }

    public function withCriteria(array $criteria): self
    {
        $self = clone $this;
        $self->criteria = $criteria;

        return $self;
    }

    public function getRequirements(): ?array
    {
        return $this->requirements;
    }

    public function withRequirements(array $requirements): self
    {
        $self = clone $this;
        $self->requirements = $requirements;

        return $self;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function withOptions(array $options): self
    {
        $self = clone $this;
        $self->options = $options;

        return $self;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function withHost(string $host): self
    {
        $self = clone $this;
        $self->host = $host;

        return $self;
    }

    public function getSchemes(): ?array
    {
        return $this->schemes;
    }

    public function withSchemes(array $schemes): self
    {
        $self = clone $this;
        $self->schemes = $schemes;

        return $self;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function withPriority(int $priority = 0): self
    {
        $self = clone $this;
        $self->priority = $priority;

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

    public function getForm(): array|string|bool|null
    {
        return $this->form;
    }

    public function withForm(array|string|bool $form): self
    {
        $self = clone $this;
        $self->form = $form;

        return $self;
    }

    public function getFactory(): bool|array|string|null
    {
        return $this->factory;
    }

    public function withFactory(array|string|bool $factory): self
    {
        $self = clone $this;
        $self->factory = $factory;

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

    public function getGrid(): ?string
    {
        return $this->grid;
    }

    public function withGrid(string $grid): self
    {
        $self = clone $this;
        $self->grid = $grid;

        return $self;
    }

    public function hasPermission(): ?bool
    {
        return $this->permission;
    }

    public function withPermission(bool $permission): self
    {
        $self = clone $this;
        $self->permission = $permission;

        return $self;
    }

    public function hasCsrfProtection(): ?bool
    {
        return $this->csrfProtection;
    }

    public function withCsrfProtection(bool $csrfProtection): self
    {
        $self = clone $this;
        $self->csrfProtection = $csrfProtection;

        return $self;
    }

    public function getRedirect(): array|string|null
    {
        return $this->redirect;
    }

    public function withRedirect(array|string $redirect): self
    {
        $self = clone $this;
        $self->redirect = $redirect;

        return $self;
    }

    public function getStateMachine(): ?array
    {
        return $this->stateMachine;
    }

    public function withStateMachine(array $stateMachine): self
    {
        $self = clone $this;
        $self->stateMachine = $stateMachine;

        return $self;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function withEvent(string $event): self
    {
        $self = clone $this;
        $self->event = $event;

        return $self;
    }

    public function hasReturnContent(): ?bool
    {
        return $this->returnContent;
    }

    public function withReturnContent(bool $returnContent): self
    {
        $self = clone $this;
        $self->returnContent = $returnContent;

        return $self;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function withResource(string $resource): self
    {
        $self = clone $this;
        $self->resource = $resource;

        return $self;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function withProvider(callable|string|null $provider): self
    {
        $self = clone $this;
        $self->provider = $provider;

        return $self;
    }

    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    public function withProcessor(callable|string|null $processor): self
    {
        $self = clone $this;
        $self->processor = $processor;

        return $self;
    }

    public function canRead(): ?bool
    {
        return $this->read;
    }

    public function withRead(bool $read): self
    {
        $self = clone $this;
        $self->read = $read;

        return $self;
    }

    public function canValidate(): ?bool
    {
        return $this->validate;
    }

    public function withValidate(bool $validate): self
    {
        $self = clone $this;
        $self->validate = $validate;

        return $self;
    }

    public function canWrite(): ?bool
    {
        return $this->write;
    }

    public function withWrite(bool $write): self
    {
        $self = clone $this;
        $self->write = $write;

        return $self;
    }

    public function canRespond(): ?bool
    {
        return $this->respond;
    }

    public function withRespond(bool $respond): self
    {
        $self = clone $this;
        $self->respond = $respond;

        return $self;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function withInput(string $input): self
    {
        $self = clone $this;
        $self->input = $input;

        return $self;
    }
}
