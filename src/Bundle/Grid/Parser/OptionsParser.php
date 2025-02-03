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

namespace Sylius\Bundle\ResourceBundle\Grid\Parser;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class OptionsParser implements OptionsParserInterface
{
    public function __construct(
        private ContainerInterface $container,
        private ExpressionLanguage $expression,
        private PropertyAccessorInterface $propertyAccessor,
    ) {
    }

    /**
     * @param array|object|null $data
     */
    public function parseOptions(array $parameters, Request $request, $data = null): array
    {
        return array_map(
            /**
             * @param mixed $parameter
             *
             * @return mixed
             */
            function ($parameter) use ($request, $data) {
                if (is_array($parameter)) {
                    return $this->parseOptions($parameter, $request, $data);
                }

                return $this->parseOption($parameter, $request, $data);
            },
            $parameters,
        );
    }

    /**
     * @param mixed $parameter
     * @param array|object|null $data
     *
     * @return mixed
     */
    private function parseOption($parameter, Request $request, $data)
    {
        if (!is_string($parameter)) {
            return $parameter;
        }

        if (str_starts_with($parameter, '$')) {
            return $request->get(substr($parameter, 1));
        }

        if (str_starts_with($parameter, 'expr:')) {
            return $this->parseOptionExpression(substr($parameter, 5), $request);
        }

        if (str_starts_with($parameter, 'resource.')) {
            return $this->parseOptionResourceField(substr($parameter, 9), $data);
        }

        if (str_starts_with($parameter, 'resource[')) {
            return $this->parseOptionResourceField(substr($parameter, 8), $data);
        }

        return $parameter;
    }

    /**
     * @return mixed
     */
    private function parseOptionExpression(string $expression, Request $request)
    {
        $expression = (string) preg_replace_callback(
            '/\$(\w+)/',
            /** @return callable */
            function (array $matches) use ($request) {
                $variable = $request->get($matches[1]);

                return is_string($variable) ? sprintf('"%s"', addslashes($variable)) : $variable;
            },
            $expression,
        );

        return $this->expression->evaluate($expression, ['container' => $this->container]);
    }

    /**
     * @param array|object|null $data
     *
     * @return mixed
     */
    private function parseOptionResourceField(string $value, $data)
    {
        return $this->propertyAccessor->getValue($data, $value);
    }
}
