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

abstract class Operation
{
    public function __construct(
        protected ?string $action = null,
        protected ?array $methods = null,
        protected ?string $name = null,
        protected ?string $path = null,
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
        protected string | array | null $form = null,
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
    ) {
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getMethods(): ?array
    {
        return $this->methods;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function getRepository(): ?array
    {
        return $this->repository;
    }

    public function getCriteria(): ?array
    {
        return $this->criteria;
    }

    public function getRequirements(): ?array
    {
        return $this->requirements;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getSchemes(): ?array
    {
        return $this->schemes;
    }

    public function getVars(): ?array
    {
        return $this->vars;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function getGrid(): ?string
    {
        return $this->grid;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    public function isRead(): ?bool
    {
        return $this->read;
    }
}
