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

namespace Sylius\Bundle\ResourceBundle\Tests\Grid\Controller;

use Pagerfanta\Pagerfanta;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Bundle\ResourceBundle\Grid\Controller\ResourcesResolver;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridViewFactoryInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

final class ResourcesResolverTest extends TestCase
{
    private ResourcesResolverInterface&MockObject $decoratedResolver;

    private GridProviderInterface&MockObject $gridProvider;

    private ResourceGridViewFactoryInterface&MockObject $gridViewFactory;

    private ResourcesResolver $resolver;

    private RequestConfiguration&MockObject $requestConfiguration;

    /** @var RepositoryInterface<ResourceInterface>&MockObject */
    private RepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->decoratedResolver = $this->createMock(ResourcesResolverInterface::class);
        $this->gridProvider = $this->createMock(GridProviderInterface::class);
        $this->gridViewFactory = $this->createMock(ResourceGridViewFactoryInterface::class);
        $this->resolver = new ResourcesResolver($this->decoratedResolver, $this->gridProvider, $this->gridViewFactory);
        $this->requestConfiguration = $this->createMock(RequestConfiguration::class);
        $this->repository = $this->createMock(RepositoryInterface::class);
    }

    public function testItImplementsResourcesResolverInterface(): void
    {
        $this->assertInstanceOf(ResourcesResolverInterface::class, $this->resolver);
    }

    public function testItUsesDecoratedResolverWhenNotUsingAGrid(): void
    {
        $resource = $this->createMock(ResourceInterface::class);

        $this->requestConfiguration->expects($this->once())
            ->method('hasGrid')
            ->willReturn(false);

        $this->decoratedResolver->expects($this->once())
            ->method('getResources')
            ->with($this->requestConfiguration, $this->repository)
            ->willReturn([$resource]);

        $result = $this->resolver->getResources($this->requestConfiguration, $this->repository);

        $this->assertSame([$resource], $result);
    }

    public function testItReturnsGridViewForHtmlRequest(): void
    {
        $gridView = $this->createMock(ResourceGridView::class);

        $this->configureGridRequest(true);
        $this->configureGridViewCreation($gridView);

        $result = $this->resolver->getResources($this->requestConfiguration, $this->repository);

        $this->assertSame($gridView, $result);
    }

    public function testItReturnsGridDataForNonHtmlRequest(): void
    {
        $gridView = $this->createMock(ResourceGridView::class);
        $paginator = $this->createMock(Pagerfanta::class);

        $this->configureGridRequest(false);
        $this->configureGridViewCreation($gridView);

        $gridView->expects($this->once())
            ->method('getData')
            ->willReturn($paginator);

        $result = $this->resolver->getResources($this->requestConfiguration, $this->repository);

        $this->assertSame($paginator, $result);
    }

    private function configureGridRequest(bool $isHtmlRequest): void
    {
        $request = new Request();
        $request->query = new InputBag(['foo' => 'bar']);

        $this->requestConfiguration->expects($this->once())
            ->method('hasGrid')
            ->willReturn(true);

        $this->requestConfiguration->expects($this->once())
            ->method('getGrid')
            ->willReturn('sylius_admin_tax_category');

        $this->requestConfiguration->expects($this->once())
            ->method('getMetadata')
            ->willReturn($this->createMock(MetadataInterface::class));

        $this->requestConfiguration->expects($this->once())
            ->method('isHtmlRequest')
            ->willReturn($isHtmlRequest);

        $this->requestConfiguration->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);
    }

    private function configureGridViewCreation(ResourceGridView $gridView): void
    {
        $gridDefinition = $this->createMock(Grid::class);

        $this->gridProvider->expects($this->once())
            ->method('get')
            ->with('sylius_admin_tax_category')
            ->willReturn($gridDefinition);

        $this->gridViewFactory->expects($this->once())
            ->method('create')
            ->with(
                $gridDefinition,
                $this->isInstanceOf(Parameters::class),
                $this->isInstanceOf(MetadataInterface::class),
                $this->requestConfiguration,
            )
            ->willReturn($gridView);
    }
}
