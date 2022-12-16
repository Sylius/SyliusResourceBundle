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

namespace Sylius\Component\Resource\Doctrine\ORM\State\Http;

use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Pagerfanta;
use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Grid\View\ContextGridView;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Resolver\ResourcesResolverInterface;
use Sylius\Component\Resource\State\ProviderInterface;

final class CollectionProvider implements ProviderInterface
{
    public function __construct(
        private ResourcesResolverInterface $resourcesResolver,
        private PagerfantaFactory $pagerfantaRepresentationFactory,
        private ContainerInterface $repositoryLocator,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        $configuration = $context->get(RequestConfigurationOption::class)->configuration();
        $metadata = $context->get(MetadataOption::class)->metadata();

        if (null === $configuration) {
            throw new \RuntimeException('Configuration was not found on the context');
        }

        if (null === $metadata) {
            throw new \RuntimeException('Metadata was not found on the context');
        }

        $repositoryId = $metadata->getServiceId('repository');

        if (!$this->repositoryLocator->has($repositoryId)) {
            throw new \RuntimeException(sprintf('Repository "%s" not found on operation "%s"', $repositoryId, $operation->getName()));
        }

        $repository = $this->repositoryLocator->get($repositoryId);

        $resources = $this->resourcesResolver->resolve($configuration, $repository);
        $paginationLimits = [];

        if ($resources instanceof ContextGridView) {
            $paginator = $resources->getData();
            $paginationLimits = $resources->getDefinition()->getLimits();
        } else {
            $paginator = $resources;
        }

        if ($paginator instanceof Pagerfanta) {
            $request = $configuration->getRequest();

            $paginator->setMaxPerPage($this->resolveMaxPerPage(
                $request->query->has('limit') ? $request->query->getInt('limit') : null,
                $configuration->getPaginationMaxPerPage(),
                $paginationLimits,
            ));
            $currentPage = (int) $request->query->get('page', '1');
            $paginator->setCurrentPage($currentPage);

            // This prevents Pagerfanta from querying database from a template
            $paginator->getCurrentPageResults();

            if (!$configuration->isHtmlRequest()) {
                $route = new Route($request->attributes->get('_route'), array_merge($request->attributes->get('_route_params'), $request->query->all()));

                return $this->pagerfantaRepresentationFactory->createRepresentation($paginator, $route);
            }
        }

        return $resources;
    }

    /**
     * @param int[] $gridLimits
     */
    private function resolveMaxPerPage(?int $requestLimit, int $configurationLimit, array $gridLimits = []): int
    {
        if (null === $requestLimit) {
            return reset($gridLimits) ?: $configurationLimit;
        }

        if (!empty($gridLimits)) {
            $maxGridLimit = max($gridLimits);

            return $requestLimit > $maxGridLimit ? $maxGridLimit : $requestLimit;
        }

        return $requestLimit;
    }
}
