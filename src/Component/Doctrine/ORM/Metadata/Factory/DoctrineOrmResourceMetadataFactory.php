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

namespace Sylius\Component\Resource\Doctrine\ORM\Metadata\Factory;

use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor;
use Sylius\Component\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Component\Resource\Doctrine\ORM\State\CollectionProvider;
use Sylius\Component\Resource\Doctrine\ORM\State\ItemProvider;
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;

class DoctrineOrmResourceMetadataFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(private RegistryInterface $resourceRegistry, private ResourceMetadataCollectionFactoryInterface $decorated)
    {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceCollectionMetadata = $this->decorated->create($resourceClass);

        /** @var ResourceMetadata $resource */
        foreach ($resourceCollectionMetadata->getIterator() as $i => $resource) {
            $operations = $resource->getOperations();

            foreach ($resource->getOperations() ?? [] as $operation) {
                $operations->add($operation->getName(), $this->addDefaults($operation));
            }

            $resource = $resource->withOperations($operations);

            $resourceCollectionMetadata[$i] = $resource;
        }

        return $resourceCollectionMetadata;
    }

    private function addDefaults(Operation $operation): Operation
    {
        $metadata = $this->resourceRegistry->get($operation->getResource());

        if (SyliusResourceBundle::DRIVER_DOCTRINE_ORM === $metadata->getDriver()) {
            $operation = $operation->withProvider($this->getProvider($operation));
        }

        if (in_array($metadata->getDriver(), [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
            SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM,
            SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM,
        ], true)) {
            $operation = $operation->withProcessor($this->getProcessor($operation));
        }

        return $operation;
    }

    private function getProvider(Operation $operation): string
    {
        if (null !== $provider = $operation->getProvider()) {
            return $provider;
        }

        if ($operation instanceof CollectionOperationInterface) {
            return CollectionProvider::class;
        }

        return ItemProvider::class;
    }

    private function getProcessor(Operation $operation): string
    {
        if (null !== $processor = $operation->getProcessor()) {
            return $processor;
        }

        if ($operation instanceof DeleteOperationInterface) {
            return RemoveProcessor::class;
        }

        return PersistProcessor::class;
    }
}
