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
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Context\Option\OperationOption;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\GridContext;

final class GridViewFactory implements GridViewFactoryInterface
{
    public function __construct(
        private DataProviderInterface $dataProvider,
    ) {
    }

    public function create(Grid $grid, Context $context, Parameters $parameters, array $driverConfiguration): GridView
    {
        return new ContextualGridView(
            $this->dataProvider->getData($grid, $parameters),
            $grid,
            $parameters,
            $this->createGridContext($context),
        );
    }

    private function createGridContext(Context $context): GridContext
    {
        $request = $context->get(RequestOption::class)?->request();

        $variables = null === $request ? [] : array_merge(
            $request->query->all(),
            $request->attributes->all('_route_params'),
        );

        return new GridContext(
            metadata: $context->get(MetadataOption::class)?->metadata(),
            variables: $variables,
            operation: $context->get(OperationOption::class)?->operation(),
            context: $context,
        );
    }
}
