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
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\Factory\VarsResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;

final class VarsResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    private VarsResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new VarsResourceMetadataCollectionFactory($this->decorated->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(VarsResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItMergeResourceVarsWithOperationVars(): void
    {
        $resource = new ResourceMetadata(
            alias: 'app.book',
            name: 'book',
            applicationName: 'app',
            vars: ['header' => 'Library', 'subheader' => 'Managing your library'],
        );

        $create = (new Create(name: 'app_book_create', vars: ['subheader' => 'Adding a new book']))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertSame([
            'header' => 'Library',
            'subheader' => 'Adding a new book',
        ], $create->getVars());
    }

    public function testItDoesNothingWhenResourceHasNoVars(): void
    {
        $resource = new ResourceMetadata(
            alias: 'app.book',
            name: 'book',
            applicationName: 'app',
        );

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->factory->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $this->assertNull($create->getVars());
    }
}
