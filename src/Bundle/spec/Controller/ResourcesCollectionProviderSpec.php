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

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
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

final class ResourcesCollectionProviderSpec extends ObjectBehavior
{
    use ProphecyTrait;

    function let(ResourcesResolverInterface $resourcesResolver): void
    {
        $this->beConstructedWith($resourcesResolver, null);
    }

    function it_implements_resources_collection_provider_interface(): void
    {
        $this->shouldImplement(ResourcesCollectionProviderInterface::class);
    }

    function it_returns_resources_resolved_from_repository(
        ResourcesResolverInterface $resourcesResolver,
        RequestConfiguration $requestConfiguration,
        RepositoryInterface $repository,
        ResourceInterface $firstResource,
        ResourceInterface $secondResource,
    ): void {
        $requestConfiguration->isHtmlRequest()->willReturn(true);

        $resourcesResolver->getResources($requestConfiguration, $repository)->willReturn([$firstResource, $secondResource]);

        $this->get($requestConfiguration, $repository)->shouldReturn([$firstResource, $secondResource]);
    }

    function it_handles_Pagerfanta(
        ResourcesResolverInterface $resourcesResolver,
        RequestConfiguration $requestConfiguration,
        RepositoryInterface $repository,
        Pagerfanta $paginator,
        Request $request,
    ): void {
        $queryParameters = new InputBag();

        $requestConfiguration->isHtmlRequest()->willReturn(true);
        $requestConfiguration->getPaginationMaxPerPage()->willReturn(5);

        $resourcesResolver->getResources($requestConfiguration, $repository)->willReturn($paginator);

        $requestConfiguration->getRequest()->willReturn($request);
        $request->query = $queryParameters;
        $queryParameters->set('limit', 5);
        $queryParameters->set('page', 6);

        $paginator->setMaxPerPage(5)->shouldBeCalled();
        $paginator->setCurrentPage(6)->shouldBeCalled();
        $paginator->getCurrentPageResults()->willReturn([]);

        $this->get($requestConfiguration, $repository)->shouldReturn($paginator);
    }

    function it_restricts_max_pagination_limit_based_on_grid_configuration(
        ResourcesResolverInterface $resourcesResolver,
        RequestConfiguration $requestConfiguration,
        RepositoryInterface $repository,
        ResourceGridView $gridView,
        Grid $grid,
        Pagerfanta $paginator,
        Request $request,
    ): void {
        $queryParameters = new InputBag();

        $requestConfiguration->isHtmlRequest()->willReturn(true);
        $requestConfiguration->getPaginationMaxPerPage()->willReturn(1000);

        $grid->getLimits()->willReturn([10, 20, 99]);

        $gridView->getDefinition()->willReturn($grid);
        $gridView->getData()->willReturn($paginator);

        $resourcesResolver->getResources($requestConfiguration, $repository)->willReturn($gridView);

        $requestConfiguration->getRequest()->willReturn($request);
        $request->query = $queryParameters;
        $queryParameters->set('limit', 1000);
        $queryParameters->set('page', 1);

        $paginator->setMaxPerPage(99)->shouldBeCalled();
        $paginator->setCurrentPage(1)->shouldBeCalled();
        $paginator->getCurrentPageResults()->willReturn([]);

        $this->get($requestConfiguration, $repository)->shouldReturn($gridView);
    }

    function it_creates_a_paginated_representation_for_pagerfanta_for_non_html_requests(
        RepositoryInterface $repository,
        MetadataInterface $metadata,
    ): void {
        if (!class_exists(PagerfantaFactory::class)) {
            throw new SkippingException('PagerfantaFactory is not installed.');
        }

        $this->beConstructedWith(new ResourcesResolver(), new PagerfantaFactory());

        $paginator = new Pagerfanta(new ArrayAdapter([]));
        $repository->createPaginator([], [])->willReturn($paginator);

        $request = new Request();
        $request->query = new InputBag(['limit' => 8, 'page' => 1]);
        $request->attributes = new ParameterBag(['_format' => 'json', '_route' => 'sylius_product_index', '_route_params' => ['slug' => 'foo-bar']]);
        $requestConfiguration = new RequestConfiguration($metadata->getWrappedObject(), $request, new Parameters(['paginate' => true]));

        $this->get($requestConfiguration, $repository)->shouldHaveType(PaginatedRepresentation::class);
    }

    function it_handles_resource_grid_view(
        ResourcesResolverInterface $resourcesResolver,
        RequestConfiguration $requestConfiguration,
        RepositoryInterface $repository,
        ResourceGridView $resourceGridView,
        Grid $grid,
        Pagerfanta $paginator,
        Request $request,
    ): void {
        $queryParameters = new InputBag();

        $requestConfiguration->isHtmlRequest()->willReturn(true);
        $requestConfiguration->getPaginationMaxPerPage()->willReturn(5);

        $resourcesResolver->getResources($requestConfiguration, $repository)->willReturn($resourceGridView);
        $resourceGridView->getData()->willReturn($paginator);

        $grid->getLimits()->willReturn([10, 25, 50]);
        $resourceGridView->getDefinition()->willReturn($grid);

        $requestConfiguration->getRequest()->willReturn($request);
        $request->query = $queryParameters;
        $queryParameters->set('limit', 5);
        $queryParameters->set('page', 6);

        $paginator->setMaxPerPage(5)->shouldBeCalled();
        $paginator->setCurrentPage(6)->shouldBeCalled();
        $paginator->getCurrentPageResults()->willReturn([]);

        $this->get($requestConfiguration, $repository)->shouldReturn($resourceGridView);
    }
}
