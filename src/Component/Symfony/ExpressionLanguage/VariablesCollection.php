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

use Webmozart\Assert\Assert;

final class VariablesCollection implements VariablesCollectionInterface
{
    /** @param iterable<int, VariablesInterface> $iterator */
    public function __construct(private iterable $iterator)
    {
        Assert::allIsInstanceOf($this->iterator, VariablesInterface::class);
    }

    public function getVariables(): array
    {
        $variables = [];

        foreach ($this->iterator as $variable) {
            $variables = array_merge($variables, $variable->getVariables());
        }

        return $variables;
    }
}
