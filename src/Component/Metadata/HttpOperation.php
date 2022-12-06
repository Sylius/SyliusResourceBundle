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

use Sylius\Component\Resource\Symfony\HttpFoundation\State\Responder;

/**
 * @experimental
 */
class HttpOperation extends Operation
{
    public function __construct(
        protected ?array $methods = null,
        protected ?string $path = null,
        protected ?string $routePrefix = null,
        ?string $name = null,
        ?string $template = null,
        ?array $repository = null,
        ?array $criteria = null,
        ?array $serializationGroups = null,
        ?string $serializationVersion = null,
        ?array $requirements = null,
        ?array $options = null,
        ?string $host = null,
        ?array $schemes = null,
        ?int $priority = null,
        ?array $vars = null,
        string | array | bool | null $form = null,
        string | array | bool | null $factory = null,
        ?string $section = null,
        ?bool $permission = null,
        ?string $grid = null,
        ?bool $csrfProtection = null,
        string | array | null $redirect = null,
        ?array $stateMachine = null,
        ?string $event = null,
        ?bool $returnContent = null,
        ?string $resource = null,
        ?string $provider = null,
        ?string $processor = null,
        ?string $responder = null,
        ?bool $read = null,
        ?bool $validate = null,
        ?bool $write = null,
        ?bool $respond = null,
        ?string $input = null,
    ) {
        parent::__construct(
            name: $name,
            template: $template,
            repository: $repository,
            criteria: $criteria,
            serializationGroups: $serializationGroups,
            serializationVersion: $serializationVersion,
            requirements: $requirements,
            options: $options,
            host: $host,
            schemes: $schemes,
            priority: $priority,
            vars: $vars,
            form: $form,
            factory: $factory,
            section: $section,
            permission: $permission,
            grid: $grid,
            csrfProtection: $csrfProtection,
            redirect: $redirect,
            stateMachine: $stateMachine,
            event: $event,
            returnContent: $returnContent,
            resource: $resource,
            provider: $provider,
            processor: $processor,
            responder: $responder ?? Responder::class,
            read: $read,
            validate: $validate,
            write: $write,
            respond: $respond,
            input: $input,
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
