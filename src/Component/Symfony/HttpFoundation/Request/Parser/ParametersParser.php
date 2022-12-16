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

namespace Sylius\Component\Resource\Symfony\HttpFoundation\Request\Parser;

use Sylius\Component\Resource\Symfony\HttpFoundation\Request\Provider\RequestParameterProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class ParametersParser implements ParametersParserInterface
{
    public function __construct(
        private ContainerInterface $container,
        private ExpressionLanguage $expression,
    ) {
    }

    public function parseRequestValues(array $parameters, Request $request): array
    {
        return array_map(
            /**
             * @param mixed $parameter
             *
             * @return mixed
             */
            function ($parameter) use ($request) {
                if (is_array($parameter)) {
                    return $this->parseRequestValues($parameter, $request);
                }

                return $this->parseRequestValue($parameter, $request);
            },
            $parameters,
        );
    }

    /**
     * @param mixed $parameter
     *
     * @return mixed
     */
    private function parseRequestValue($parameter, Request $request)
    {
        if (!is_string($parameter)) {
            return $parameter;
        }

        if (0 === strpos($parameter, '$')) {
            return RequestParameterProvider::provide($request, substr($parameter, 1));
        }

        if (0 === strpos($parameter, 'expr:')) {
            return $this->parseRequestValueExpression(substr($parameter, 5), $request);
        }

        if (0 === strpos($parameter, '!!')) {
            return $this->parseRequestValueTypecast($parameter, $request);
        }

        return $parameter;
    }

    /** @return mixed */
    private function parseRequestValueExpression(string $expression, Request $request)
    {
        $expression = (string) preg_replace_callback(
            '/(\$\w+)/',
            /**
             * @return mixed
             */
            function (array $matches) use ($request) {
                $variable = $request->get(substr($matches[1], 1));

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

    /** @return mixed */
    private function parseRequestValueTypecast(string $parameter, Request $request)
    {
        [$typecast, $castedValue] = explode(' ', $parameter, 2);

        /** @var callable $castFunctionName */
        $castFunctionName = substr($typecast, 2) . 'val';

        Assert::oneOf($castFunctionName, ['intval', 'floatval', 'boolval'], 'Variable can be casted only to int, float or bool.');

        return $castFunctionName($this->parseRequestValue($castedValue, $request));
    }
}
