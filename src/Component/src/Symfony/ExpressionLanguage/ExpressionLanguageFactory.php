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
final class ExpressionLanguageFactory
{
    /** @param iterable<int, ExpressionFunctionProviderInterface> $providers */
    public function __construct(private $providers)
    {
        Assert::allIsInstanceOf($this->providers, ExpressionFunctionProviderInterface::class);
    }

    public function __invoke(): ExpressionLanguage
    {
        $expressionLanguage = new ExpressionLanguage();

        foreach ($this->providers as $provider) {
            $expressionLanguage->registerProvider($provider);
        }

        return $expressionLanguage;
    }
}
