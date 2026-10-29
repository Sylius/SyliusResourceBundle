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

namespace Sylius\Resource\Tests\Grid\View\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Grid\View\Factory\ContextualGridViewFactory;
use Sylius\Resource\Grid\View\Factory\ContextualGridViewFactoryInterface;
use Sylius\Resource\Grid\View\GridContext;

final class ContextualGridViewFactoryTest extends TestCase
{
    public function testItCreatesAGridViewFromAGridContext(): void
    {
        $driverConfiguration = ['repository' => ['method' => 'createQueryBuilderByProductId', 'arguments' => ['$productId']]];
        $grid = Grid::fromCodeAndDriverConfiguration('sylius_admin_product_variant', 'doctrine/orm', $driverConfiguration);
        $parameters = new Parameters(['page' => 2]);
        $gridContext = new GridContext(section: 'admin', variables: ['productId' => 42]);

        $dataProvider = $this->createMock(DataProviderInterface::class);
        $dataProvider->expects($this->once())->method('getData')->with($grid, $parameters)->willReturn(['variants']);

        $factory = new ContextualGridViewFactory($dataProvider);
        $gridView = $factory->create($grid, $parameters, $gridContext);

        $this->assertInstanceOf(ContextualGridViewFactoryInterface::class, $factory);
        $this->assertSame(['variants'], $gridView->getData());
        $this->assertSame($grid, $gridView->getDefinition());
        $this->assertSame($parameters, $gridView->getParameters());
        $this->assertSame($gridContext, $gridView->getGridContext());
        // The legacy "$name" values are resolved by the resource bundle (see LegacyContextualGridViewFactory)
        $this->assertSame($driverConfiguration, $grid->getDriverConfiguration());
    }
}
