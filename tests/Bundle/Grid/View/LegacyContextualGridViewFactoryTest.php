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

namespace Sylius\Bundle\ResourceBundle\Tests\Grid\View;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParser;
use Sylius\Bundle\ResourceBundle\Grid\Parser\GridExpressionParser;
use Sylius\Bundle\ResourceBundle\Grid\View\LegacyContextualGridViewFactory;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\Factory\ContextualGridViewFactoryInterface;
use Sylius\Resource\Grid\View\GridContext;
use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class LegacyContextualGridViewFactoryTest extends TestCase
{
    public function testItResolvesTheDriverConfigurationWithTheGridContextVariables(): void
    {
        $grid = Grid::fromCodeAndDriverConfiguration('sylius_admin_product_variant', 'doctrine/orm', [
            'repository' => [
                'method' => 'createQueryBuilderByProductId',
                'arguments' => ['en_US', '$productId'],
            ],
        ]);
        $parameters = new Parameters(['page' => 2]);
        $gridContext = new GridContext(section: 'admin', variables: ['productId' => 42]);
        $gridView = new ContextualGridView([], $grid, $parameters, $gridContext);

        $decorated = $this->createMock(ContextualGridViewFactoryInterface::class);
        $decorated->expects($this->once())->method('create')->with($grid, $parameters, $gridContext)->willReturn($gridView);

        $factory = new LegacyContextualGridViewFactory($decorated, new ParametersParser(new Container(), new ExpressionLanguage()));

        $this->assertInstanceOf(ContextualGridViewFactoryInterface::class, $factory);
        $this->assertSame($gridView, $factory->create($grid, $parameters, $gridContext));
        $this->assertSame(
            ['repository' => ['method' => 'createQueryBuilderByProductId', 'arguments' => ['en_US', 42]]],
            $grid->getDriverConfiguration(),
        );
    }

    public function testItEvaluatesTheExpressionsOfTheDriverConfigurationWithTheGridContextVariables(): void
    {
        $grid = Grid::fromCodeAndDriverConfiguration('sylius_admin_product_variant', 'doctrine/orm', [
            'repository' => [
                'method' => 'createQueryBuilderByProductId',
                'arguments' => ['$productId', "@=variables['productId'] * 2"],
            ],
        ]);
        $gridContext = new GridContext(variables: ['productId' => 42]);

        $decorated = $this->createMock(ContextualGridViewFactoryInterface::class);
        $decorated->method('create')->willReturn(new ContextualGridView([], $grid, new Parameters(), $gridContext));

        $factory = new LegacyContextualGridViewFactory(
            $decorated,
            new ParametersParser(new Container(), new ExpressionLanguage()),
            new GridExpressionParser(new ArgumentParser(new ExpressionLanguage(), new VariablesCollection([]))),
        );
        $factory->create($grid, new Parameters(), $gridContext);

        $this->assertSame(
            ['repository' => ['method' => 'createQueryBuilderByProductId', 'arguments' => [42, 84]]],
            $grid->getDriverConfiguration(),
        );
    }
}
