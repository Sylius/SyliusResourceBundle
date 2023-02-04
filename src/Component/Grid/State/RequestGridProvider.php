<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Grid\State;

use Pagerfanta\Pagerfanta;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Grid\View\Factory\GridViewFactoryInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProviderInterface;

final class RequestGridProvider implements ProviderInterface
{
    public function __construct(
        private GridViewFactoryInterface $gridViewFactory,
        private GridProviderInterface $gridProvider,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        $grid = $operation->getGrid();

        if (null === $grid) {
            throw new \RuntimeException(sprintf('Operation has no grid, so you cannot use this provider for operation "%s"', $operation->getName() ?? ''));
        }

        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return null;
        }

        $gridDefinition = $this->gridProvider->get($grid);
        $gridConfiguration = $gridDefinition->getDriverConfiguration();

        $parameters = $request->query->all();
        $gridView = $this->gridViewFactory->create($gridDefinition, $context, new Parameters($parameters), $gridConfiguration);

        $data = $gridView->getData();

        if ($data instanceof Pagerfanta) {
            $currentPage = $request->query->getInt('page', 1);
            $data->setCurrentPage($currentPage);
        }

        return $gridView;
    }
}
