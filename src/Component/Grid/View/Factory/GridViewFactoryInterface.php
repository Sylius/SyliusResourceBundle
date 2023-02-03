<?php


declare(strict_types=1);

namespace Sylius\Component\Resource\Grid\View\Factory;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;
interface GridViewFactoryInterface
{
    public function create(Grid $grid, Parameters $parameters, array $driverConfiguration): GridView;
}
