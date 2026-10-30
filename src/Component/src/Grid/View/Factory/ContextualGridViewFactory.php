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

use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\GridContext;

/**
 * @experimental
 */
final class ContextualGridViewFactory implements ContextualGridViewFactoryInterface
{
    public function __construct(
        private DataProviderInterface $dataProvider,
    ) {
    }

    public function create(Grid $grid, Parameters $parameters, GridContext $gridContext): ContextualGridView
    {
        return new ContextualGridView($this->dataProvider->getData($grid, $parameters), $grid, $parameters, $gridContext);
    }
}
