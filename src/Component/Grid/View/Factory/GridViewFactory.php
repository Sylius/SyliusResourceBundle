<?php


declare(strict_types=1);

namespace Sylius\Component\Resource\Grid\View\Factory;

use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;

final class GridViewFactory implements GridViewFactoryInterface
{
    public function __construct(
        private DataProviderInterface $dataProvider
    ) {
    }

    public function create(Grid $grid, Parameters $parameters, array $driverConfiguration): GridView
    {
        return new GridView($this->dataProvider->getData($grid, $parameters), $grid, $parameters);
    }
}
