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

namespace Sylius\Bundle\ResourceBundle\Grid\ExpressionLanguage;

use Sylius\Component\Grid\ExpressionLanguage\VariablesCollectionInterface as GridVariablesCollectionInterface;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollectionInterface;

/**
 * Makes the expression variables of the resource bundle tagged with "sylius.grid_variables" ("request", "token",
 * "user", "sylius_repositories" and the application ones) available in the grid expressions of sylius/grid-bundle.
 *
 * @experimental
 */
final class GridExpressionVariables implements GridVariablesCollectionInterface
{
    public function __construct(private VariablesCollectionInterface $variablesCollection)
    {
    }

    public function getCollection(): array
    {
        return $this->variablesCollection->getVariables();
    }
}
