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

namespace spec\Sylius\Component\Resource\Metadata\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Component\Resource\Metadata\Resource\Factory\TemplatesDirResourceMetadataCollectionFactory;
use Sylius\Component\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Component\Resource\Metadata\Show;

final class TemplatesDirResourceMetadataCollectionFactorySpec extends ObjectBehavior
{
    function let(ResourceMetadataCollectionFactoryInterface $decorated): void
    {
        $this->beConstructedWith($decorated);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TemplatesDirResourceMetadataCollectionFactory::class);
    }

    function it_uses_default_templates_dir(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $this->beConstructedWith($decorated, ['default_templates_dir' => 'crud']);

        $resource = new Resource(alias: 'app.book');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getTemplate()->shouldReturn('crud/create.html.twig');

        $show = $resourceMetadataCollection->getOperation('app.book', 'app_book_show');
        $show->getTemplate()->shouldReturn('crud/show.html.twig');
    }

    function it_uses_resource_templates_dir(
        ResourceMetadataCollectionFactoryInterface $decorated,
    ): void {
        $resource = new Resource(alias: 'app.book', templatesDir: 'book');

        $create = (new Create(name: 'app_book_create'))->withResource($resource);
        $show = (new Show(name: 'app_book_show'))->withResource($resource);

        $resource = $resource->withOperations(new Operations([
            $create->getName() => $create,
            $show->getName() => $show,
        ]));

        $resourceMetadataCollection = new ResourceMetadataCollection();
        $resourceMetadataCollection[] = $resource;

        $decorated->create('App\Resource')->willReturn($resourceMetadataCollection);

        $resourceMetadataCollection = $this->create('App\Resource');

        $create = $resourceMetadataCollection->getOperation('app.book', 'app_book_create');
        $create->getTemplate()->shouldReturn('book/create.html.twig');

        $show = $resourceMetadataCollection->getOperation('app.book', 'app_book_show');
        $show->getTemplate()->shouldReturn('book/show.html.twig');
    }
}
