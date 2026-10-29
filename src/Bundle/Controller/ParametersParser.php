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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Bundle\ResourceBundle\Provider\RequestParameterProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class ParametersParser implements ParametersParserInterface, VariablesParametersParserInterface
{
    use BcLayerRequestTrait;

    private ContainerInterface $container;

    private ExpressionLanguage $expression;

    public function __construct(ContainerInterface $container, ExpressionLanguage $expression)
    {
        $this->container = $container;
        $this->expression = $expression;
    }

    public function parseRequestValues(array $parameters, Request $request): array
    {
        return $this->parseValues(
            $parameters,
            static fn (string $key): mixed => RequestParameterProvider::provide($request, $key),
            fn (string $key): mixed => $this->getFromRequest($request, $key),
        );
    }

    /**
     * @param array<array-key, mixed> $parameters
     * @param array<string, mixed> $variables
     *
     * @return array<array-key, mixed>
     */
    public function parseVariablesValues(array $parameters, array $variables): array
    {
        $resolveVariable = static fn (string $key): mixed => $variables[$key] ?? null;

        return $this->parseValues($parameters, $resolveVariable, $resolveVariable);
    }

    /**
     * The request values and the expression variables are not resolved the same way, for BC reasons.
     *
     * @param array<array-key, mixed> $parameters
     * @param callable(string): mixed $resolveValue
     * @param callable(string): mixed $resolveExpressionVariable
     *
     * @return array<array-key, mixed>
     */
    private function parseValues(array $parameters, callable $resolveValue, callable $resolveExpressionVariable): array
    {
        return array_map(
            /**
             * @param mixed $parameter
             *
             * @return mixed
             */
            function ($parameter) use ($resolveValue, $resolveExpressionVariable) {
                if (is_array($parameter)) {
                    return $this->parseValues($parameter, $resolveValue, $resolveExpressionVariable);
                }

                return $this->parseValue($parameter, $resolveValue, $resolveExpressionVariable);
            },
            $parameters,
        );
    }

    /**
     * @param mixed $parameter
     * @param callable(string): mixed $resolveValue
     * @param callable(string): mixed $resolveExpressionVariable
     *
     * @return mixed
     */
    private function parseValue($parameter, callable $resolveValue, callable $resolveExpressionVariable)
    {
        if (!is_string($parameter)) {
            return $parameter;
        }

        if (0 === strpos($parameter, '$')) {
            return $resolveValue(substr($parameter, 1));
        }

        if (0 === strpos($parameter, 'expr:')) {
            return $this->parseValueExpression(substr($parameter, 5), $resolveExpressionVariable);
        }

        if (0 === strpos($parameter, '!!')) {
            return $this->parseValueTypecast($parameter, $resolveValue, $resolveExpressionVariable);
        }

        return $parameter;
    }

    /**
     * @param callable(string): mixed $resolveVariable
     *
     * @return mixed
     */
    private function parseValueExpression(string $expression, callable $resolveVariable)
    {
        $expression = (string) preg_replace_callback(
            '/(\$\w+)/',
            /**
             * @return mixed
             */
            function (array $matches) use ($resolveVariable) {
                $variable = $resolveVariable(substr($matches[1], 1));

                if (is_array($variable) || is_object($variable)) {
                    throw new \InvalidArgumentException(sprintf(
                        'Cannot use %s ($%s) as parameter in expression.',
                        gettype($variable),
                        $matches[1],
                    ));
                }

                return is_string($variable) ? sprintf('"%s"', addslashes($variable)) : $variable;
            },
            $expression,
        );

        return $this->expression->evaluate($expression, ['container' => $this->container]);
    }

    /**
     * @param callable(string): mixed $resolveValue
     * @param callable(string): mixed $resolveExpressionVariable
     *
     * @return mixed
     */
    private function parseValueTypecast(string $parameter, callable $resolveValue, callable $resolveExpressionVariable)
    {
        [$typecast, $castedValue] = explode(' ', $parameter, 2);

        /** @var callable $castFunctionName */
        $castFunctionName = substr($typecast, 2) . 'val';

        Assert::oneOf($castFunctionName, ['intval', 'floatval', 'boolval'], 'Variable can be casted only to int, float or bool.');

        return $castFunctionName($this->parseValue($castedValue, $resolveValue, $resolveExpressionVariable));
    }
}
