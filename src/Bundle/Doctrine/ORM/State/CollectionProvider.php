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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ORM\State;

use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\State\ProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CollectionProvider implements ProviderInterface
{
    public function __construct(
        private ResourcesCollectionProviderInterface $resourcesCollectionProvider,
        private ManagerRegistry $managerRegistry,
    ) {
    }

    public function provide(RequestConfiguration $configuration)
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->managerRegistry->getRepository($configuration->getMetadata()->getClass('model'));

        return $this->resourcesCollectionProvider->get(
            $configuration,
            $repository,
        );
    }
}
