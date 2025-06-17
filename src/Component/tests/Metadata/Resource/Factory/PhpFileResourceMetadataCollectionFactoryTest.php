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

namespace Sylius\Resource\Tests\Metadata\Resource\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Extractor\PhpFileResourceExtractor;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\Routing\Factory\RouteName\OperationRouteNameFactory;

final class PhpFileResourceMetadataCollectionFactoryTest extends TestCase
{
    private PhpFileResourceMetadataCollectionFactory $factory;

    private RegistryInterface $resourceRegistry;

    protected function setUp(): void
    {
        $this->resourceRegistry = $this->createMock(RegistryInterface::class);
        $this->factory = new PhpFileResourceMetadataCollectionFactory($this->resourceRegistry, new OperationRouteNameFactory(), new PhpFileResourceExtractor([__DIR__ . '/php/php_file_with_resource_class.php']));
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(PhpFileResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItCreatesResourceMetadata(): void
    {
        $metadata = Metadata::fromAliasAndConfiguration('app.dummy', ['driver' => 'dummy_driver']);
        $this->resourceRegistry->method('get')->willReturn($metadata);

        $metadataCollection = $this->factory->create(\stdClass::class);
        $this->assertInstanceOf(ResourceMetadataCollection::class, $metadataCollection);
        $this->assertCount(1, $metadataCollection);

        $resource = $metadataCollection->getIterator()->current();
        $this->assertInstanceOf(ResourceMetadata::class, $resource);
        $this->assertSame(\stdClass::class, $resource->getClass());
    }
}
