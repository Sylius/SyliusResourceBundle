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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ODM\MongoDB;

use Doctrine\MongoDB\Query\Builder as QueryBuilder;
use Doctrine\ODM\MongoDB\DocumentRepository as BaseDocumentRepository;
use Pagerfanta\Doctrine\MongoDBODM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

@trigger_error(sprintf('The "%s" class is deprecated since Sylius 1.3. Doctrine MongoDB and PHPCR support will no longer be supported in Sylius 2.0.', DocumentRepository::class), \E_USER_DEPRECATED);

/**
 * Doctrine ODM driver resource manager.
 */
class DocumentRepository extends BaseDocumentRepository implements RepositoryInterface
{
    /**
     * @param int $id
     */
    public function find($id): object
    {
        return $this
            ->getQueryBuilder()
            ->field('id')->equals(new \MongoId($id))
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findAll(): iterable
    {
        return $this
            ->getCollectionQueryBuilder()
            ->getQuery()
            ->getIterator()
        ;
    }

    public function findOneBy(array $criteria): object
    {
        $queryBuilder = $this->getQueryBuilder();

        $this->applyCriteria($queryBuilder, $criteria);

        return $queryBuilder
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @param int $limit
     * @param int $offset
     */
    public function findBy(array $criteria, ?array $sorting = null, $limit = null, $offset = null): iterable
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        if (null !== $limit) {
            $queryBuilder->limit($limit);
        }

        if (null !== $offset) {
            $queryBuilder->skip($offset);
        }

        return $queryBuilder
            ->getQuery()
            ->getIterator()
        ;
    }

    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    public function add(ResourceInterface $resource): void
    {
        $this->dm->persist($resource);
        $this->dm->flush();
    }

    public function remove(ResourceInterface $resource): void
    {
        if (null !== $this->find($resource->getId())) {
            $this->dm->remove($resource);
            $this->dm->flush();
        }
    }

    public function getPaginator(QueryBuilder $queryBuilder): Pagerfanta
    {
        return new Pagerfanta(new QueryAdapter($queryBuilder));
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder();
    }

    protected function getCollectionQueryBuilder(): QueryBuilder\
    {
        return $this->createQueryBuilder();
    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            $queryBuilder->field($property)->equals($value);
        }
    }

    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            $queryBuilder->sort($property, $order);
        }
    }
}
