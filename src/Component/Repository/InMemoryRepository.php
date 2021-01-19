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

namespace Sylius\Component\Resource\Repository;

use ArrayObject;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\Exception\ExistingResourceException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class InMemoryRepository implements RepositoryInterface
{
    /** @var PropertyAccessor */
    protected $accessor;

    /** @var ArrayObject */
    protected $arrayObject;

    /** @psalm-var class-string */
    protected $interface;

    /**
     * @psalm-param class-string $interface
     *
     * @throws \InvalidArgumentException
     * @throws UnexpectedTypeException
     */
    public function __construct(string $interface)
    {
        if (!in_array(ResourceInterface::class, class_implements($interface) ?: [], true)) {
            throw new UnexpectedTypeException($interface, ResourceInterface::class);
        }

        $this->interface = $interface;
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->arrayObject = new ArrayObject();
    }

    /**
     * @throws ExistingResourceException
     * @throws UnexpectedTypeException
     */
    public function add(ResourceInterface $resource): void
    {
        if (!$resource instanceof $this->interface) {
            throw new UnexpectedTypeException($resource, $this->interface);
        }

        if (in_array($resource, $this->findAll(), true)) {
            throw new ExistingResourceException();
        }

        $this->arrayObject->append($resource);
    }

    public function remove(ResourceInterface $resource): void
    {
        $newResources = array_filter($this->findAll(), static function ($object) use ($resource) {
            return $object !== $resource;
        });

        $this->arrayObject->exchangeArray($newResources);
    }

    public function find($id): ?object
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findAll(): array
    {
        return $this->arrayObject->getArrayCopy();
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $results = $this->findAll();

        if (!empty($criteria)) {
            $results = $this->applyCriteria($results, $criteria);
        }

        if (!empty($orderBy)) {
            $results = $this->applyOrder($results, $orderBy);
        }

        return array_slice($results, $offset ?? 0, $limit);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function findOneBy(array $criteria): ?ResourceInterface
    {
        if (empty($criteria)) {
            throw new \InvalidArgumentException('The criteria array needs to be set.');
        }

        $results = $this->applyCriteria($this->findAll(), $criteria);

        /** @var ResourceInterface|false $result */
        $result = reset($results);
        if ($result !== false) {
            return $result;
        }

        return null;
    }

    public function getClassName(): string
    {
        return $this->interface;
    }

    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        $resources = $this->findAll();

        if (!empty($sorting)) {
            $resources = $this->applyOrder($resources, $sorting);
        }

        if (!empty($criteria)) {
            $resources = $this->applyCriteria($resources, $criteria);
        }

        return new Pagerfanta(new ArrayAdapter($resources));
    }

    /**
     * @param object[] $resources
     *
     * @return object[]|array
     */
    private function applyCriteria(array $resources, array $criteria): array
    {
        foreach ($this->arrayObject as $object) {
            foreach ($criteria as $criterion => $value) {
                if ($value !== $this->accessor->getValue($object, $criterion)) {
                    $key = array_search($object, $resources);
                    unset($resources[$key]);
                }
            }
        }

        return $resources;
    }

    /**
     * @param object[] $resources
     *
     * @return object[]
     */
    private function applyOrder(array $resources, array $orderBy): array
    {
        $results = $resources;

        $arguments = [];
        foreach ($orderBy as $property => $order) {
            $sortable = [];

            foreach ($results as $key => $object) {
                $sortable[$key] = $this->accessor->getValue($object, $property);
            }

            $arguments[] = $sortable;

            if (RepositoryInterface::ORDER_ASCENDING === $order) {
                $arguments[] = \SORT_ASC;
            } elseif (RepositoryInterface::ORDER_DESCENDING === $order) {
                $arguments[] = \SORT_DESC;
            } else {
                throw new \InvalidArgumentException('Unknown order.');
            }
        }

        $arguments[] = &$results;

        /**
         * Doing PHP magic, it works this way
         *
         * @psalm-suppress InvalidPassByReference
         * @psalm-suppress PossiblyInvalidArgument
         */
        array_multisort(...$arguments);

        return $results;
    }
}
