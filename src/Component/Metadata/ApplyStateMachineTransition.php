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

use Sylius\Component\Resource\Symfony\State\ApplyStateMachineProcessor;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class ApplyStateMachineTransition extends HttpOperation implements UpdateOperationInterface
{
    private ?string $transition;

    private ?string $graph;

    public function __construct(
        ?string $name = null,
        ?string $transition = null,
        ?string $graph = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $template = null,
        ?array $repository = null,
        ?array $criteria = null,
        ?array $requirements = null,
        ?array $options = null,
        ?string $host = null,
        ?array $schemes = null,
        ?int $priority = null,
        ?array $vars = null,
        string | array | bool | null $factory = null,
        ?string $section = null,
        ?bool $permission = null,
        ?bool $csrfProtection = null,
        string | array | null $redirect = null,
        protected ?array $stateMachine = null,
        ?string $event = null,
        ?bool $returnContent = null,
        ?string $resource = null,
        ?string $provider = null,
        ?string $processor = null,
        ?bool $read = null,
        ?bool $write = null,
        ?bool $respond = null,
        ?string $input = null,
    ) {
        $this->transition = $transition;
        $this->graph = $graph;

        parent::__construct(
            methods: ['PUT', 'PATCH'],
            path: $path,
            routePrefix: $routePrefix,
            name: $name ?? $transition,
            template: $template,
            repository: $repository,
            criteria: $criteria,
            requirements: $requirements,
            options: $options,
            host: $host,
            schemes: $schemes,
            priority: $priority,
            vars: $vars,
            form: false,
            factory: $factory,
            section: $section,
            permission: $permission,
            csrfProtection: $csrfProtection,
            redirect: $redirect,
            stateMachine: $stateMachine,
            event: $event,
            returnContent: $returnContent,
            resource: $resource,
            provider: $provider,
            processor: $processor ?? ApplyStateMachineProcessor::class,
            read: $read,
            validate: false,
            write: $write,
            respond: $respond,
            input: $input,
        );
    }

    public function getTransition(): ?string
    {
        return $this->transition;
    }

    public function withTransition(string $transition): self
    {
        $self = clone $this;
        $self->name = $self->name ?? $transition;
        $self->stateMachine = array_merge($self->stateMachine ?? [], ['transition' => $transition]);

        return $self;
    }

    public function getGraph(): ?string
    {
        return $this->graph;
    }

    public function withGraph(string $transition): self
    {
        $self = clone $this;
        $self->stateMachine = array_merge($self->stateMachine ?? [], ['graph' => $transition]);

        return $self;
    }
}
