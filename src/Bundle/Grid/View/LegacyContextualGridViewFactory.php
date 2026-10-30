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

namespace Sylius\Bundle\ResourceBundle\Grid\View;

use Sylius\Bundle\ResourceBundle\Controller\VariablesParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\Parser\LegacyGridExpressionParser;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\Factory\ContextualGridViewFactoryInterface;
use Sylius\Resource\Grid\View\GridContext;

/**
 * BC layer: resolves the legacy values of the grid driver configuration for the request-free grid views.
 *
 * The legacy "$name", "expr:..." and "!!int" values (e.g. the "$productId" repository argument) are resolved
 * as ResourceGridViewFactory does, but from the grid context variables, then the "@=" expressions that the legacy
 * parser leaves unevaluated (see LegacyGridExpressionParser). It lives in the bundle, as the legacy parsers do,
 * while the grid context and the factory it decorates live in the component.
 *
 * It will go away with the legacy parsers, when the driver configuration is resolved by sylius/grid-bundle
 * (VariablesResolver).
 *
 * @experimental
 */
final class LegacyContextualGridViewFactory implements ContextualGridViewFactoryInterface
{
    public function __construct(
        private ContextualGridViewFactoryInterface $decorated,
        private VariablesParametersParserInterface $parametersParser,
        private ?LegacyGridExpressionParser $expressionParser = null,
    ) {
    }

    public function create(Grid $grid, Parameters $parameters, GridContext $gridContext): ContextualGridView
    {
        $driverConfiguration = $this->parametersParser->parseVariablesValues($grid->getDriverConfiguration(), $gridContext->getVariables());

        if (null !== $this->expressionParser) {
            $driverConfiguration = $this->expressionParser->parseExpressions($driverConfiguration, $gridContext->getVariables());
        }

        $grid->setDriverConfiguration($driverConfiguration);

        return $this->decorated->create($grid, $parameters, $gridContext);
    }
}
