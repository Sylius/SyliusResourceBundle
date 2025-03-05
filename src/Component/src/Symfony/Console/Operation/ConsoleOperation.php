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

namespace Sylius\Resource\Symfony\Console\Operation;

use Sylius\Resource\Metadata\Operation;

abstract class ConsoleOperation extends Operation
{
    public function __construct(
        private ?string $commandName = null,
        ?string $shortName = null,
        ?string $name = null,
        callable|string|null $provider = null,
        callable|string|null $processor = null,
        callable|string|null $responder = null,
        callable|string|null $repository = null,
        ?string $repositoryMethod = null,
        ?array $repositoryArguments = null,
        ?bool $read = null,
        ?bool $write = null,
        ?bool $validate = null,
        ?string $formType = null,
        ?array $formOptions = null,
        ?array $validationContext = null,
        ?string $eventShortName = null,
        ?string $notificationMessage = null,
    ) {
        parent::__construct(
            shortName: $shortName,
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
            formType: $formType,
            formOptions: $formOptions,
            validationContext: $validationContext,
            eventShortName: $eventShortName,
            notificationMessage: $notificationMessage,
        );
    }

    public function getCommandName(): ?string
    {
        return $this->commandName;
    }

    public function withCommandName(string $commandName): self
    {
        $self = clone $this;
        $self->commandName = $commandName;

        return $self;
    }
}
