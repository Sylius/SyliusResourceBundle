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

namespace Sylius\Resource\Tests\Metadata\Resource;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;

final class ResourceMetadataCollectionTest extends TestCase
{
    private ResourceMetadataCollection $collection;

    protected function setUp(): void
    {
        $resourceMetadata = (new ResourceMetadata('app.dummy'))->withOperations(
            new Operations([
                'app_dummy_index' => new Index(),
                'app_dummy_create' => new Create(),
            ]),
        );

        $this->collection = new ResourceMetadataCollection([$resourceMetadata]);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ResourceMetadataCollection::class, $this->collection);
    }

    public function testItCanGetAResourceOperation(): void
    {
        $operation = $this->collection->getOperation('app.dummy', 'app_dummy_index');
        $this->assertInstanceOf(Index::class, $operation);
    }

    public function testItThrowsAnExceptionWhenOperationWasNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Operation "app_dummy_not_found" for "app.dummy" resource was not found.');

        $this->collection->getOperation('app.dummy', 'app_dummy_not_found');
    }
}
