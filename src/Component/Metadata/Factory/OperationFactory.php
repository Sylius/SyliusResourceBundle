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
use Webmozart\Assert\Assert;

final class OperationFactory implements OperationFactoryInterface
{
    public function create(string $operationClass, array $options): Operation
    {
        Assert::true(is_a($operationClass, Operation::class, true));

        // TODO ensure operation arguments has default values
        $operation = new $operationClass();

        return $this->withOptions($operation, $options);
    }

    private function withOptions(Operation $operation, array $options): Operation
    {
        $reflection = new \ReflectionClass($operation);

        foreach ($options as $key => $value) {
            $method = 'with' . ucfirst($key);

            if (!$reflection->hasMethod($method)) {
                continue;
            }

            $operation = $operation->{$method}($value);
        }

        return $operation;
    }
}
