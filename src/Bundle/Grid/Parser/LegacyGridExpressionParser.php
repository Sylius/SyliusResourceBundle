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

use Sylius\Component\Grid\ExpressionLanguage\ExpressionEvaluatorInterface;

/**
 * BC layer: evaluates the "@=" expressions that the legacy parsers leave unevaluated.
 *
 * The resource grids (ResourceGridView, and the context-aware views through the resource renderers
 * and LegacyContextualGridViewFactory) are still parsed by the legacy OptionsParser / ParametersParser
 * ("$name", "expr:", "resource."). Those leave the "@=" values as is, so they are evaluated afterwards
 * (driver configuration, action options), with the expression evaluator of sylius/grid-bundle and the same
 * variables as the other grids: "variables" (the page variables, e.g. "variables['productId']"), "resource" for the
 * actions, and the resource bundle ones ("request", "token", "user", "sylius_repositories", "throw_not_found_on_null",
 * see GridExpressionVariables).
 *
 * It will go away with the legacy parsers, when the resource grids are parsed by sylius/grid-bundle
 * (ActionOptionsParser, VariablesResolver).
 *
 * @experimental
 */
final class LegacyGridExpressionParser
{
    public function __construct(private ExpressionEvaluatorInterface $expressionEvaluator)
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

                return $this->expressionEvaluator->evaluateExpression(
                    substr($value, 2),
                    array_merge($expressionVariables, ['variables' => $variables]),
                );
            },
            $values,
        );
    }
}
