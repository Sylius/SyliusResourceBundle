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
use Sylius\Resource\Metadata\Resource\Factory\TemplatesDirResourceMetadataCollectionFactory;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Show;

final class TemplatesDirResourceMetadataCollectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ResourceMetadataCollectionFactoryInterface|ObjectProphecy $decorated;

    private TemplatesDirResourceMetadataCollectionFactory $factory;

    protected function setUp(): void
    {
        $this->decorated = $this->prophesize(ResourceMetadataCollectionFactoryInterface::class);
        $this->factory = new TemplatesDirResourceMetadataCollectionFactory($this->decorated->reveal());
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(TemplatesDirResourceMetadataCollectionFactory::class, $this->factory);
    }

    public function testItUsesDefaultTemplatesDir(): void
    {
        $this->factory = new TemplatesDirResourceMetadataCollectionFactory($this->decorated->reveal(), ['default_templates_dir' => 'crud']);

        $resource = new ResourceMetadata(alias: 'app.book');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $result = $this->factory->create('App\Resource');

        $create = $result->getOperation('app.book', 'app_book_create');
        $this->assertSame('crud/create.html.twig', $create->getTemplate());

        $show = $result->getOperation('app.book', 'app_book_show');
        $this->assertSame('crud/show.html.twig', $show->getTemplate());
    }

    public function testItUsesResourceTemplatesDir(): void
    {
        $resource = new ResourceMetadata(alias: 'app.book', templatesDir: 'book');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $this->decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $result = $this->factory->create('App\Resource');

        $create = $result->getOperation('app.book', 'app_book_create');
        $this->assertSame('book/create.html.twig', $create->getTemplate());

        $show = $result->getOperation('app.book', 'app_book_show');
        $this->assertSame('book/show.html.twig', $show->getTemplate());
    }
}
