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

namespace Sylius\Resource\Grid\View;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;

/**
 * @experimental
 */
final class ContextualGridView extends GridView implements GridContextAwareInterface
{
    public function __construct(
        mixed $data,
        Grid $definition,
        Parameters $parameters,
        private GridContext $gridContext,
    ) {
        // The grid context variables are the grid view ones, e.g. "variables['productId']" in the grid expressions
        parent::__construct($data, $definition, $parameters, $gridContext->getVariables());
    }

    public function getGridContext(): GridContext
    {
        return $this->gridContext;
    }
}
