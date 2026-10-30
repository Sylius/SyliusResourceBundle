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
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\Factory\ContextualGridViewFactoryInterface;
use Sylius\Resource\Grid\View\GridContext;

/**
 * Resolves the legacy "$name", "expr:..." and "!!int" values of the grid driver configuration
 * (e.g. the "$productId" repository argument), as ResourceGridViewFactory does, but from the grid context variables.
 *
 * BC layer: it keeps the legacy syntax of the grid configurations working with the request-free grid views.
 * It lives in the bundle, as the legacy parsers do, while the grid context and the factory it decorates
 * live in the component. It will go away with the legacy parsers, in favour of the ArgumentParser.
 *
 * @experimental
 */
final class LegacyContextualGridViewFactory implements ContextualGridViewFactoryInterface
{
    public function __construct(
        private ContextualGridViewFactoryInterface $decorated,
        private VariablesParametersParserInterface $parametersParser,
    ) {
    }

    public function create(Grid $grid, Parameters $parameters, GridContext $gridContext): ContextualGridView
    {
        $grid->setDriverConfiguration($this->parametersParser->parseVariablesValues(
            $grid->getDriverConfiguration(),
            $gridContext->getVariables(),
        ));

        return $this->decorated->create($grid, $parameters, $gridContext);
    }
}
