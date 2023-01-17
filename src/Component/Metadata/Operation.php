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
    /** @var string|callable|null */
    protected $provider;

    /** @var string|callable|null */
    protected $processor;

    public function __construct(
        protected ?string $name = null,
        protected ?string $template = null,
        string|callable|null $provider = null,
        string|callable|null $processor = null,
    ) {
        $this->provider = $provider;
        $this->processor = $processor;
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
}
