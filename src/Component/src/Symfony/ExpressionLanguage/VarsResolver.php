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

/**
 * @experimental
 */
final class VarsResolver implements VarsResolverInterface
{
    public function __construct(
        private readonly ArgumentParser $argumentParser,
    ) {
    }

    public function resolve(array $vars): array
    {
        foreach ($vars as $key => $value) {
            if (\is_array($value)) {
                $vars[$key] = $this->resolve($value);

                continue;
            }

            // Parse only vars that contain expressions
            if (!str_starts_with($value, '@=')) {
                continue;
            }

            $value = str_replace('@=', '', $value);

            $vars[$key] = $this->argumentParser->parseExpression($value);
        }

        return $vars;
    }
}
