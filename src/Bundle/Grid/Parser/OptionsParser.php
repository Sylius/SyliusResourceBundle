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

namespace Sylius\Bundle\ResourceBundle\Grid\Parser;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class OptionsParser implements OptionsParserInterface
{
    private ContainerInterface $container;

    private ExpressionLanguage $expression;

    private PropertyAccessorInterface $propertyAccessor;

    public function __construct(
        ContainerInterface $container,
        ExpressionLanguage $expression,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->container = $container;
        $this->expression = $expression;
        $this->propertyAccessor = $propertyAccessor;
    }

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
            $parameters
        );
    }

    /**
     * @param mixed $parameter
     * @param mixed $data
     *
     * @return mixed
     */
    private function parseOption($parameter, Request $request, $data)
    {
        if (!is_string($parameter)) {
            return $parameter;
        }

        if (0 === strpos($parameter, '$')) {
            return $request->get(substr($parameter, 1));
        }

        if (0 === strpos($parameter, 'expr:')) {
            return $this->parseOptionExpression(substr($parameter, 5), $request);
        }

        if (0 === strpos($parameter, 'resource.')) {
            return $this->parseOptionResourceField(substr($parameter, 9), $data);
        }

        if (0 === strpos($parameter, 'resource[')) {
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
            /**
             * @return mixed
             */
            function (array $matches) use ($request) {
                $variable = $request->get($matches[1]);

                return is_string($variable) ? sprintf('"%s"', addslashes($variable)) : $variable;
            },
            $expression
        );

        return $this->expression->evaluate($expression, ['container' => $this->container]);
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    private function parseOptionResourceField(string $value, $data)
    {
        return $this->propertyAccessor->getValue($data, $value);
    }
}
