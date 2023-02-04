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
abstract class Operation
{
    private ?Resource $resource = null;

    /** @var string|callable|null */
    protected $provider;

    /** @var string|callable|null */
    protected $processor;

    /** @var string|callable|null */
    protected $responder;

    /** @var string|callable|null */
    protected $repository;

    public function __construct(
        protected ?string $template = null,
        protected ?string $shortName = null,
        protected ?string $name = null,
        string|callable|null $provider = null,
        string|callable|null $processor = null,
        string|callable|null $responder = null,
        string|callable|null $repository = null,
        protected ?string $repositoryMethod = null,
        protected ?bool $read = null,
        protected ?bool $write = null,
        protected ?string $formType = null,
        protected ?array $formOptions = null,
    ) {
        $this->provider = $provider;
        $this->processor = $processor;
        $this->responder = $responder;
        $this->repository = $repository;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function withResource(Resource $resource): self
    {
        $self = clone $this;
        $self->resource = $resource;

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

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function withShortName(string $shortName): self
    {
        $self = clone $this;
        $self->shortName = $shortName;

        return $self;
    }

    public function getProvider(): callable|string|null
    {
        return $this->provider;
    }

    public function withProvider(string|callable|null $provider): self
    {
        $self = clone $this;
        $self->provider = $provider;

        return $self;
    }

    public function getProcessor(): callable|string|null
    {
        return $this->processor;
    }

    public function withProcessor(string|callable|null $processor): self
    {
        $self = clone $this;
        $self->processor = $processor;

        return $self;
    }

    public function getResponder(): callable|string|null
    {
        return $this->responder;
    }

    public function withResponder(string|callable|null $responder): self
    {
        $self = clone $this;
        $self->responder = $responder;

        return $self;
    }

    public function getRepository(): callable|string|null
    {
        return $this->repository;
    }

    public function withRepository(string|callable|null $repository): self
    {
        $self = clone $this;
        $self->repository = $repository;

        return $self;
    }

    public function getRepositoryMethod(): ?string
    {
        return $this->repositoryMethod;
    }

    public function withRepositoryMethod(string $repositoryMethod): self
    {
        $self = clone $this;
        $self->repositoryMethod = $repositoryMethod;

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

    public function getFormOptions(): ?array
    {
        return $this->formOptions;
    }

    public function withFormOptions(array $formOptions): self
    {
        $self = clone $this;
        $self->formOptions = $formOptions;

        return $self;
    }
}
