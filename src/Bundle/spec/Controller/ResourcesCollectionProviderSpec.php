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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PhpSpec\Exception\Example\SkippingException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProvider;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolver;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class ResourcesCollectionProviderTest extends TestCase
{
    /** @var ResourcesResolverInterface|MockObject */
    private MockObject $resourcesResolverMock;

    private ResourcesCollectionProvider $resourcesCollectionProvider;

    use ProphecyTrait;

    protected function setUp(): void
    {
        $this->resourcesResolverMock = $this->createMock(ResourcesResolverInterface::class);
        $this->resourcesCollectionProvider = new ResourcesCollectionProvider($this->resourcesResolverMock, null);
    }

    public function testImplementsResourcesCollectionProviderInterface(): void
    {
        $this->assertInstanceOf(ResourcesCollectionProviderInterface::class, $this->resourcesCollectionProvider);
    }

    public function testReturnsResourcesResolvedFromRepository(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $firstResourceMock */
        $firstResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceInterface|MockObject $secondResourceMock */
        $secondResourceMock = $this->createMock(ResourceInterface::class);
        $this->resourcesResolverMock->expects($this->once())->method('getResources')->with($requestConfigurationMock, $repositoryMock)->willReturn([$firstResourceMock, $secondResourceMock]);
        $this->assertSame([$firstResourceMock, $secondResourceMock], $this->resourcesCollectionProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testHandlesPagerfanta(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var \Pagerfanta\Pagerfanta|MockObject $paginatorMock */
        $paginatorMock = $this->createMock(Pagerfanta::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        $queryParameters = new InputBag();
        $queryParameters->set('limit', 5);
        $queryParameters->set('page', 6);
        $requestConfigurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getPaginationMaxPerPage')->willReturn(5);
        $this->resourcesResolverMock->expects($this->once())->method('getResources')->with($requestConfigurationMock, $repositoryMock)->willReturn($paginatorMock);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->query = $queryParameters;
        $this->assertEquals(5, $requestMock->query->get('limit'));
        $this->assertEquals(6, $requestMock->query->get('page'));
        $paginatorMock->expects($this->once())->method('setMaxPerPage')->with(5);
        $paginatorMock->expects($this->once())->method('setCurrentPage')->with(6);
        $paginatorMock->expects($this->once())->method('getCurrentPageResults')->willReturn([]);
        $this->assertSame($paginatorMock, $this->resourcesCollectionProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testRestrictsMaxPaginationLimitBasedOnGridConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceGridView|MockObject $gridViewMock */
        $gridViewMock = $this->createMock(ResourceGridView::class);
        /** @var Grid|MockObject $gridMock */
        $gridMock = $this->createMock(Grid::class);
        /** @var \Pagerfanta\Pagerfanta|MockObject $paginatorMock */
        $paginatorMock = $this->createMock(Pagerfanta::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        $queryParameters = new InputBag();
        $queryParameters->set('limit', 1000);
        $queryParameters->set('page', 1);
        $requestConfigurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getPaginationMaxPerPage')->willReturn(1000);
        $gridMock->expects($this->once())->method('getLimits')->willReturn([10, 20, 99]);
        $gridViewMock->expects($this->once())->method('getDefinition')->willReturn($gridMock);
        $gridViewMock->expects($this->once())->method('getData')->willReturn($paginatorMock);
        $this->resourcesResolverMock->expects($this->once())->method('getResources')->with($requestConfigurationMock, $repositoryMock)->willReturn($gridViewMock);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->query = $queryParameters;
        $this->assertEquals(1000, $requestMock->query->get('limit'));
        $this->assertEquals(1, $requestMock->query->get('page'));
        $paginatorMock->expects($this->once())->method('setMaxPerPage')->with(99);
        $paginatorMock->expects($this->once())->method('setCurrentPage')->with(1);
        $paginatorMock->expects($this->once())->method('getCurrentPageResults')->willReturn([]);
        $this->assertSame($gridViewMock, $this->resourcesCollectionProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testCreatesAPaginatedRepresentationForPagerfantaForNonHtmlRequests(): void
    {
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        if (!class_exists(PagerfantaFactory::class)) {
            throw new SkippingException('PagerfantaFactory is not installed.');
        }
        $this->resourcesCollectionProvider = new ResourcesCollectionProvider(new ResourcesResolver(), new PagerfantaFactory());
        $paginator = new Pagerfanta(new ArrayAdapter([]));
        $repositoryMock->expects($this->once())->method('createPaginator')->with([], [])->willReturn($paginator);
        $request = new Request();
        $request->query = new InputBag(['limit' => 8, 'page' => 1]);
        $request->attributes = new ParameterBag(['_format' => 'json', '_route' => 'sylius_product_index', '_route_params' => ['slug' => 'foo-bar']]);
        $requestConfiguration = new RequestConfiguration($metadataMock, $request, new Parameters(['paginate' => true]));
        $this->assertInstanceOf(PaginatedRepresentation::class, $this->resourcesCollectionProvider->get($requestConfiguration, $repositoryMock));
    }

    public function testHandlesResourceGridView(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceGridView|MockObject $resourceGridViewMock */
        $resourceGridViewMock = $this->createMock(ResourceGridView::class);
        /** @var Grid|MockObject $gridMock */
        $gridMock = $this->createMock(Grid::class);
        /** @var \Pagerfanta\Pagerfanta|MockObject $paginatorMock */
        $paginatorMock = $this->createMock(Pagerfanta::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        $queryParameters = new InputBag();
        $queryParameters->set('limit', 5);
        $queryParameters->set('page', 6);
        $requestConfigurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getPaginationMaxPerPage')->willReturn(5);
        $this->resourcesResolverMock->expects($this->once())->method('getResources')->with($requestConfigurationMock, $repositoryMock)->willReturn($resourceGridViewMock);
        $resourceGridViewMock->expects($this->once())->method('getData')->willReturn($paginatorMock);
        $gridMock->expects($this->once())->method('getLimits')->willReturn([10, 25, 50]);
        $resourceGridViewMock->expects($this->once())->method('getDefinition')->willReturn($gridMock);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->query = $queryParameters;
        $this->assertEquals(5, $requestMock->query->get('limit'));
        $this->assertEquals(6, $requestMock->query->get('page'));
        $paginatorMock->expects($this->once())->method('setMaxPerPage')->with(5);
        $paginatorMock->expects($this->once())->method('setCurrentPage')->with(6);
        $paginatorMock->expects($this->once())->method('getCurrentPageResults')->willReturn([]);
        $this->assertSame($resourceGridViewMock, $this->resourcesCollectionProvider->get($requestConfigurationMock, $repositoryMock));
    }
}
