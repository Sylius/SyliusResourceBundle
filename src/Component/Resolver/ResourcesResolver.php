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

namespace Sylius\Component\Resource\Resolver;

use Psr\Container\ContainerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\MetadataOption;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Metadata\Operation;

final class ResourcesResolver implements ResourcesResolverInterface
{
    public function __construct(private ContainerInterface $repositoryLocator)
    {
    }

    public function resolve(Operation $operation, Context $context): iterable|object
    {
        $metadata = $context->get(MetadataOption::class)?->metadata();

        if (null === $metadata) {
            throw new \RuntimeException('Metadata must be configured.');
        }

        $repositoryId = $metadata->getServiceId('repository');

        if (!$this->repositoryLocator->has($repositoryId)) {
            throw new \RuntimeException('Repository must be configured.');
        }

        $repository = $this->repositoryLocator->get($repositoryId);

        $method = $operation->getRepository()['method'] ?? $operation->getRepository() ?? null;
        if (null !== $method) {
            if (is_array($method) && 2 === count($method)) {
                $repository = $method[0];
                $method = $method[1];
            }

            $arguments = array_values($operation->getRepository()['arguments'] ?? []);

            return $repository->$method(...$arguments);
        }

        $requestConfiguration = $context->get(RequestConfigurationOption::class)?->configuration();

        if (null === $requestConfiguration) {
            throw new \RuntimeException('Configuration must be configured.');
        }

        $criteria = [];
        if ($requestConfiguration->isFilterable()) {
            $criteria = $requestConfiguration->getCriteria();
        }

        $sorting = [];
        if ($requestConfiguration->isSortable()) {
            $sorting = $requestConfiguration->getSorting();
        }

        if ($requestConfiguration->isPaginated()) {
            return $repository->createPaginator($criteria, $sorting);
        }

        return $repository->findBy($criteria, $sorting, $requestConfiguration->getLimit());
    }
}
