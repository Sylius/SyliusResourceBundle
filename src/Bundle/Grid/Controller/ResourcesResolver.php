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

namespace Sylius\Bundle\ResourceBundle\Grid\Controller;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

final class ResourcesResolver implements ResourcesResolverInterface
{
    public function __construct(
        private ResourcesResolverInterface $decoratedResolver,
        private GridProviderInterface $gridProvider,
        private ResourceGridViewFactoryInterface $gridViewFactory,
    ) {
    }

    /**
     * @psalm-suppress MissingReturnType
     */
    public function getResources(RequestConfiguration $requestConfiguration, RepositoryInterface $repository)
    {
        if (!$requestConfiguration->hasGrid()) {
            return $this->decoratedResolver->getResources($requestConfiguration, $repository);
        }

        $gridDefinition = $this->gridProvider->get($requestConfiguration->getGrid());

        $request = $requestConfiguration->getRequest();
        $parameters = new Parameters($request->query->all());

        $gridView = $this->gridViewFactory->create($gridDefinition, $parameters, $requestConfiguration->getMetadata(), $requestConfiguration);

        if ($requestConfiguration->isHtmlRequest()) {
            return $gridView;
        }

        return $gridView->getData();
    }
}
