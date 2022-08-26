<?php

declare(strict_types=1);

namespace Sylius\Component\Resource\Metadata\Factory;

use Sylius\Component\Resource\Metadata\Operation;
use Webmozart\Assert\Assert;

final class OperationFactory implements OperationFactoryInterface
{
    public function create(string $operationClass, array $arguments): Operation
    {
        Assert::true(is_a($operationClass, Operation::class, true));

        $values = $this->getParametersMap($operationClass);

        $values = array_merge($values, $arguments);

        return new $operationClass(...\array_values($values));
    }

    private function getParametersMap(string $operationClass): array
    {
        $reflection = new \ReflectionClass($operationClass);

        $values = [];

        foreach ($reflection->getConstructor()->getParameters() as $reflectionParameter) {
            $values[$reflectionParameter->getName()] = $reflectionParameter->getDefaultValue();
        }

        return $values;
    }
}
