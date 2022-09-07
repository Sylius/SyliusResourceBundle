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

use Sylius\Component\Resource\Exception\OperationNotFoundException;

final class ResourceMetadata
{
    private array $operationCache = [];

    public function __construct(private ?Resource $resource = null)
    {
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

    public function getOperation(string $operationName): Operation
    {
        if (isset($this->operationCache[$operationName])) {
            return $this->operationCache[$operationName];
        }

        foreach ($this->resource->getOperations() ?? [] as $operation) {
            if ($operationName === $operation->getName()) {
                return $this->operationCache[$operationName] = $operation;
            }

            if ($operationName === $operation->getAction()) {
                return $this->operationCache[$operationName] = $operation;
            }
        }

        throw new OperationNotFoundException(sprintf('Operation "%s" not found.', $operationName));
    }
}
