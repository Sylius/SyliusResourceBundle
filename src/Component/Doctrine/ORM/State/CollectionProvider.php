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

namespace Sylius\Component\Resource\Doctrine\ORM\State;

use Psr\Container\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\State\ProviderInterface;

final class CollectionProvider implements ProviderInterface
{
    public function __construct(
        private ResourcesCollectionProviderInterface $resourcesCollectionProvider,
        private ContainerInterface $repositoryLocator,
    ) {
    }

    public function provide(RequestConfiguration $configuration): mixed
    {
        $metadata = $configuration->getMetadata();
        $repositoryId = $metadata->getServiceId('repository');

        if (!$this->repositoryLocator->has($repositoryId)) {
            throw new \RuntimeException(sprintf('Repository "%s" not found on operation "%s"', $repositoryId, $configuration->getOperation()));
        }

        /** @var RepositoryInterface $repository */
        $repository = $this->repositoryLocator->get($repositoryId);

        return $this->resourcesCollectionProvider->get(
            $configuration,
            $repository,
        );
    }
}
