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

namespace Sylius\Resource\Symfony\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class ArgumentParser implements ArgumentParserInterface
{
    public function __construct(
        private readonly ExpressionLanguage $expressionLanguage,
        private readonly VariablesCollectionInterface $variablesCollection,
        ?iterable $providers = null,
    ) {
        foreach ($providers ?? [] as $provider) {
            Assert::isInstanceOf($provider, ExpressionFunctionProviderInterface::class);

            $this->expressionLanguage->registerProvider($provider);
        }
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
