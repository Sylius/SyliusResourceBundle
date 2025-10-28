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

use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class VariablesCollectionAggregate implements VariablesCollectionInterface
{
    /** @param iterable<int, VariablesCollectionInterface> $variablesCollection */
    public function __construct(private $variablesCollection)
    {
        Assert::allIsInstanceOf($this->variablesCollection, VariablesCollectionInterface::class);
    }

    public function getCollection(): array
    {
        $collections = [];
        foreach ($this->variablesCollection as $collection) {
            $collections[] = $collection->getCollection();
        }

        return array_merge(...$collections);
    }
}
