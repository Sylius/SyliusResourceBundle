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

namespace Sylius\Resource\Grid\State;

use Pagerfanta\Pagerfanta;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\View\Factory\GridViewFactoryInterface;
use Sylius\Resource\Metadata\GridAwareOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProviderInterface;

final class RequestGridProvider implements ProviderInterface
{
    public function __construct(
        private ?GridViewFactoryInterface $gridViewFactory = null,
        private ?GridProviderInterface $gridProvider = null,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        if (null === $this->gridViewFactory || null === $this->gridProvider) {
            throw new \LogicException('You can not use a grid if Sylius Grid Bundle is not available. Try running "composer require sylius/grid-bundle".');
        }

        if (!$operation instanceof GridAwareOperationInterface) {
            throw new \LogicException(sprintf('You can not use a grid if your operation does not implement "%s".', GridAwareOperationInterface::class));
        }

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

            $maxPerPage = $this->resolveMaxPerPage(
                $request->query->has('limit') ? $request->query->getInt('limit') : null,
                $gridDefinition->getLimits(),
            );
            $data->setMaxPerPage($maxPerPage);
        }

        return $gridView;
    }

    private function resolveMaxPerPage(?int $requestLimit, array $gridLimits = []): int
    {
        if (null === $requestLimit) {
            $firstGridLimit = reset($gridLimits);

            return false === $firstGridLimit ? 10 : $firstGridLimit;
        }

        if (!empty($gridLimits)) {
            $maxGridLimit = max($gridLimits);

            // Cannot retrieve more items than configured in the grid
            return min($requestLimit, $maxGridLimit);
        }

        return $requestLimit;
    }
}
