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

namespace Sylius\Resource\Symfony\Request;

use Sylius\Resource\Reflection\Filter\FunctionArgumentsFilter;
use Symfony\Component\HttpFoundation\Request;

final class RepositoryArgumentResolver
{
    public function getArguments(Request $request, \ReflectionFunctionAbstract $reflector): array
    {
        $allArguments = [
            $request->attributes->all('_route_params'),
            $request->query->all(),
            $request->request->all(),
        ];

        foreach ($allArguments as $arguments) {
            $matchedArguments = FunctionArgumentsFilter::filter($reflector, $arguments);

            if (0 === count($matchedArguments) && $this->hasOnlyOneRequiredArrayParameter($reflector)) {
                $arguments = $this->filterPrivateArguments($arguments);

                return [$arguments];
            }

            if ('__call' === $reflector->getName()) {
                $arguments = $this->filterPrivateArguments($arguments);

                if ([] === $arguments) {
                    continue;
                }

                return array_values($arguments);
            }

            if ([] === $matchedArguments) {
                continue;
            }

            return $matchedArguments;
        }

        return [];
    }

    /**
     * @param array<string, mixed> $arguments
     */
    private function filterPrivateArguments(array $arguments): array
    {
        return array_filter($arguments, function (string $key): bool {
            return !str_starts_with($key, '_');
        }, \ARRAY_FILTER_USE_KEY);
    }

    private function hasOnlyOneRequiredArrayParameter(\ReflectionFunctionAbstract $reflector): bool
    {
        /** @var array|\ReflectionParameter[] $parameters */
        $parameters = $reflector->getParameters();

        $parameters = array_filter($parameters, function ($parameter): bool {
            return !$parameter->isDefaultValueAvailable();
        });

        if (1 !== \count($parameters)) {
            return false;
        }

        $parameterType = $parameters[0]->getType()?->__toString();

        return 'array' === $parameterType;
    }
}
