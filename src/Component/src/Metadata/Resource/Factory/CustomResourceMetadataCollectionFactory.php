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

namespace Sylius\Resource\Metadata\Resource\Factory;

use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\Operation\OperationUpdaterInterface;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Webmozart\Assert\Assert;

final class CustomResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    use OperationDefaultsTrait;

    public function __construct(
        private readonly OperationUpdaterInterface $metadataUpdater,
        private readonly ?ResourceMetadataCollectionFactoryInterface $decorated = null,
    ) {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceMetadataCollection = new ResourceMetadataCollection();
        if ($this->decorated) {
            $resourceMetadataCollection = $this->decorated->create($resourceClass);
        }

        $newMetadataCollection = new ResourceMetadataCollection();

        /** @var ResourceMetadata $resourceMetadata */
        foreach ($resourceMetadataCollection as $resourceMetadata) {
            $operations = $resourceMetadata->getOperations() ?? new Operations();

            /** @var Operation $operation */
            foreach ($operations as $operation) {
                $operationName = $operation->getName();
                Assert::notNull($operationName);
                $operations->add($operationName, $this->metadataUpdater->update($operation));
            }

            $newMetadataCollection[] = $resourceMetadata->withOperations($operations);
        }

        return $newMetadataCollection;
    }
}
