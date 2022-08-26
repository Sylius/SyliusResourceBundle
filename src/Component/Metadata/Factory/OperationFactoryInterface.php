<?php

declare(strict_types=1);

namespace Sylius\Component\Resource\Metadata\Factory;

use Sylius\Component\Resource\Metadata\Operation;

interface OperationFactoryInterface
{
    /**
     * @psalm-param class-string $operationClass
     */
    public function create(string $operationClass, array $arguments): Operation;
}
