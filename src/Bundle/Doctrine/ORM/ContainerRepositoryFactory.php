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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Repository\RepositoryFactory;
use Doctrine\Persistence\ObjectRepository;

final class ContainerRepositoryFactory implements RepositoryFactory
{
    private RepositoryFactory $doctrineFactory;

    /** @var string[] */
    private array $genericEntities;

    /** @var ObjectRepository[] */
    private array $managedRepositories = [];

    /**
     * @param string[] $genericEntities
     */
    public function __construct(RepositoryFactory $doctrineFactory, array $genericEntities)
    {
        $this->doctrineFactory = $doctrineFactory;
        $this->genericEntities = $genericEntities;
    }

    /** @psalm-suppress InvalidReturnType */
    public function getRepository(EntityManagerInterface $entityManager, $entityName): ObjectRepository
    {
        $metadata = $entityManager->getClassMetadata($entityName);

        if ($metadata->customRepositoryClassName === null && in_array($entityName, $this->genericEntities, true)) {
            /** @psalm-suppress InvalidReturnStatement */
            return $this->getOrCreateRepository($entityManager, $metadata);
        }

        return $this->doctrineFactory->getRepository($entityManager, $entityName);
    }

    private function getOrCreateRepository(
        EntityManagerInterface $entityManager,
        ClassMetadata $metadata,
    ): ObjectRepository {
        $repositoryHash = $metadata->getName() . spl_object_hash($entityManager);

        if (!isset($this->managedRepositories[$repositoryHash])) {
            $this->managedRepositories[$repositoryHash] = new EntityRepository($entityManager, $metadata);
        }

        return $this->managedRepositories[$repositoryHash];
    }
}
