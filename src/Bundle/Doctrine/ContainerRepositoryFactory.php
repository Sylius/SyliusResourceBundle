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

namespace Sylius\Bundle\ResourceBundle\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ContainerRepositoryFactory as DoctrineContainerRepositoryFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Repository\RepositoryFactory;
use Doctrine\Persistence\ObjectRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class ContainerRepositoryFactory implements RepositoryFactory
{
    /** @var ObjectRepository[] */
    private $managedRepositories = [];

    /** @var string[] */
    private $genericEntities;

    /** @var DoctrineContainerRepositoryFactory */
    private $doctrineFactory;

    /**
     * @param string[] $genericEntities
     */
    public function __construct(DoctrineContainerRepositoryFactory $doctrineFactory, array $genericEntities)
    {
        $this->doctrineFactory = $doctrineFactory;
        $this->genericEntities = $genericEntities;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName): ObjectRepository
    {
        if (in_array($entityName, $this->genericEntities, true)) {
            $metadata = $entityManager->getClassMetadata($entityName);

            return $this->getOrCreateRepository($entityManager, $metadata);
        }

        return $this->doctrineFactory->getRepository($entityManager, $entityName);
    }

    private function getOrCreateRepository(
        EntityManagerInterface $entityManager,
        ClassMetadata $metadata
    ): ObjectRepository {
        $repositoryHash = $metadata->getName() . spl_object_hash($entityManager);

        if (isset($this->managedRepositories[$repositoryHash])) {
            return $this->managedRepositories[$repositoryHash];
        }

        return $this->managedRepositories[$repositoryHash] = new EntityRepository($entityManager, $metadata);
    }
}
