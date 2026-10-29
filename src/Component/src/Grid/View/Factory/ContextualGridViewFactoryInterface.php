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

namespace Sylius\Resource\Grid\View\Factory;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\GridContext;

/**
 * Creates a grid view without any request, e.g. from the props of a live component.
 *
 * @experimental
 */
interface ContextualGridViewFactoryInterface
{
    public function create(Grid $grid, Parameters $parameters, GridContext $gridContext): ContextualGridView;
}
