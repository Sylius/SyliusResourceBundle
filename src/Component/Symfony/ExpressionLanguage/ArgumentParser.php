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

namespace Sylius\Component\Resource\Symfony\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ArgumentParser implements ArgumentParserInterface
{
    public function __construct(
        private ExpressionLanguage $expressionLanguage,
        private VariablesCollectionInterface $variablesCollection,
    ) {
    }

    public function parseExpression(string $expression, array $variables = []): mixed
    {
        return $this->expressionLanguage->evaluate(
            $expression,
            array_merge(
                $this->variablesCollection->getVariables(),
                $variables,
            ),
        );
    }
}
