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

namespace Sylius\Component\Resource\Metadata\Factory;

use Sylius\Component\Resource\Metadata\Operation;
use Symfony\Component\HttpFoundation\Request;
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

    public function createFromRequest(Request $request): Operation
    {
        $attributes = $request->attributes->all('_sylius');

        return new Operation(
            action: $attributes['operation'] ?? null,
            methods: [$request->getMethod()],
            path: $request->getRequestUri(),
            vars: $attributes['vars'] ?? null,
            section: $attributes['section'] ?? null,
            resource: $attributes['resource'] ?? null,
            provider: $attributes['provider'] ?? null,
            processor: $attributes['processor'] ?? null,
            read: $attributes['read'] ?? null,
            validate: $attributes['validate'] ?? null,
            write: $attributes['write'] ?? null,
            respond: $attributes['respond'] ?? null,
            input: $attributes['input'] ?? null,
        );
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
