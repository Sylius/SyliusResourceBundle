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

use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParserInterface;

/**
 * Evaluates the "@=expression" values of the grid configuration (driver configuration, action options) with the
 * new expression syntax of the metadata-based routing (ArgumentParser), the grid variables being available as
 * "variables", e.g. "@=sylius_repositories.get('sylius.repository.product').find(variables['productId'])".
 *
 * The grid variables are the route parameters of the page for both the regular and the live grids,
 * whereas "request" is the live component request during the live re-renders.
 *
 * The other values (the legacy "$name", "expr:" and "resource." ones) are left as is.
 *
 * @experimental
 */
final class GridExpressionParser
{
    public function __construct(private ArgumentParserInterface $argumentParser)
    {
    }

    /**
     * @template TKey of array-key
     *
     * @param array<TKey, mixed> $values
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressionVariables Extra variables, e.g. the "resource" of an action
     *
     * @return array<TKey, mixed>
     */
    public function parseExpressions(array $values, array $variables, array $expressionVariables = []): array
    {
        return array_map(
            function (mixed $value) use ($variables, $expressionVariables): mixed {
                if (is_array($value)) {
                    return $this->parseExpressions($value, $variables, $expressionVariables);
                }

                if (!is_string($value) || !str_starts_with($value, '@=')) {
                    return $value;
                }

                return $this->argumentParser->parseExpression(
                    substr($value, 2),
                    array_merge($expressionVariables, ['variables' => $variables]),
                );
            },
            $values,
        );
    }
}
