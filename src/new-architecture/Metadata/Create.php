<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Resource\Metadata;

/**
 * @experimental
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class Create extends HttpOperation implements CreateOperationInterface, StateMachineAwareOperationInterface, FactoryAwareOperationInterface
{
    /** @var string|callable|false|null */
    private $factory;

    public function __construct(
        ?array $methods = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $template = null,
        ?string $shortName = null,
        ?string $name = null,
        string|callable|null $provider = null,
        string|callable|null $processor = null,
        string|callable|null $responder = null,
        string|callable|null $repository = null,
        ?array $repositoryArguments = null,
        ?string $repositoryMethod = null,
        string|callable|false|null $factory = null,
        private ?string $factoryMethod = null,
        private ?array $factoryArguments = [],
        ?bool $read = null,
        ?bool $write = null,
        ?bool $validate = null,
        ?bool $deserialize = null,
        ?bool $serialize = null,
        ?string $formType = null,
        ?array $formOptions = null,
        ?array $validationContext = null,
        ?string $eventShortName = null,
        string|callable|null $twigContextFactory = null,
        ?string $redirectToRoute = null,
        ?array $redirectArguments = null,
        ?array $vars = null,
        private ?string $stateMachineComponent = null,
        private ?string $stateMachineTransition = null,
        private ?string $stateMachineGraph = null,
    ) {
        parent::__construct(
            methods: $methods ?? ['GET', 'POST'],
            path: $path,
            routePrefix: $routePrefix,
            template: $template,
            shortName: $shortName ?? 'create',
            name: $name,
            provider: $provider,
            processor: $processor,
            responder: $responder,
            repository: $repository,
            repositoryMethod: $repositoryMethod,
            repositoryArguments: $repositoryArguments,
            read: $read,
            write: $write,
            validate: $validate,
            deserialize: $deserialize,
            serialize: $serialize,
            formType: $formType,
            formOptions: $formOptions,
            validationContext: $validationContext,
            eventShortName: $eventShortName,
            twigContextFactory: $twigContextFactory,
            redirectToRoute: $redirectToRoute,
            redirectArguments: $redirectArguments,
            vars: $vars,
        );

        $this->factory = $factory;
    }

    public function getStateMachineComponent(): ?string
    {
        return $this->stateMachineComponent;
    }

    public function withStateMachineComponent(?string $stateMachineComponent): self
    {
        $self = clone $this;
        $self->stateMachineComponent = $stateMachineComponent;

        return $self;
    }

    public function getStateMachineTransition(): ?string
    {
        return $this->stateMachineTransition;
    }

    public function withStateMachineTransition(string $stateMachineTransition): self
    {
        $self = clone $this;
        $self->stateMachineTransition = $stateMachineTransition;

        return $self;
    }

    public function getStateMachineGraph(): ?string
    {
        return $this->stateMachineGraph;
    }

    public function withStateMachineGraph(string $stateMachineGraph): self
    {
        $self = clone $this;
        $self->stateMachineGraph = $stateMachineGraph;

        return $self;
    }

    public function getFactory(): callable|string|false|null
    {
        return $this->factory;
    }

    public function withFactory(string|callable|false|null $factory): self
    {
        $self = clone $this;
        $self->factory = $factory;

        return $self;
    }

    public function getFactoryMethod(): ?string
    {
        return $this->factoryMethod;
    }

    public function withFactoryMethod(string $factoryMethod): self
    {
        $self = clone $this;
        $self->factoryMethod = $factoryMethod;

        return $self;
    }

    public function getFactoryArguments(): ?array
    {
        return $this->factoryArguments;
    }

    public function withFactoryArguments(array $factoryArguments): self
    {
        $self = clone $this;
        $self->factoryArguments = $factoryArguments;

        return $self;
    }
}
