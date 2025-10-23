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

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolver;
use PHPUnit\Framework\MockObject\MockObject;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesResolverInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;

final class ResourcesResolverTest extends TestCase
{
    private ResourcesResolver $resourcesResolver;
    protected function setUp(): void
    {
        $this->resourcesResolver = new ResourcesResolver();
    }
    function testImplementsResourcesResolverInterface(): void
    {
        $this->assertInstanceOf(ResourcesResolverInterface::class, $this->resourcesResolver);
    }

    function testGetsAllResourcesIfHasNoCriteria(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $firstResourceMock */
        $firstResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceInterface|MockObject $secondResourceMock */
        $secondResourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('isPaginated')->willReturn(false);
        $requestConfigurationMock->expects($this->once())->method('isFilterable')->willReturn(false);
        $requestConfigurationMock->expects($this->once())->method('isSortable')->willReturn(false);
        $requestConfigurationMock->expects($this->once())->method('getLimit')->willReturn(null);
        $repositoryMock->expects($this->once())->method('findBy')->with([], [], null)->willReturn([$firstResourceMock, $secondResourceMock]);
        $this->assertSame([$firstResourceMock, $secondResourceMock], $this->resourcesResolver->getResources($requestConfigurationMock, $repositoryMock));
    }

    function testFindsResourcesByCriteriaIfNotPaginated(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $firstResourceMock */
        $firstResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceInterface|MockObject $secondResourceMock */
        $secondResourceMock = $this->createMock(ResourceInterface::class);
        /** @var ResourceInterface|MockObject $thirdResourceMock */
        $thirdResourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('isPaginated')->willReturn(false);
        $requestConfigurationMock->expects($this->once())->method('isFilterable')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('isSortable')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getLimit')->willReturn(15);
        $requestConfigurationMock->expects($this->once())->method('getCriteria')->willReturn(['custom' => 'criteria']);
        $requestConfigurationMock->expects($this->once())->method('getSorting')->willReturn(['name' => 'desc']);
        $repositoryMock->expects($this->once())->method('findBy')->with(['custom' => 'criteria'], ['name' => 'desc'], 15)->willReturn([$firstResourceMock, $secondResourceMock, $thirdResourceMock]);
        $this->assertSame([$firstResourceMock, $secondResourceMock, $thirdResourceMock], $this->resourcesResolver->getResources($requestConfigurationMock, $repositoryMock));
    }

    function testUsesCustomMethodAndArgumentsIfSpecified(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $firstResourceMock */
        $firstResourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn('findAll');
        $requestConfigurationMock->expects($this->once())->method('getRepositoryArguments')->willReturn(['foo']);
        $repositoryMock->expects($this->once())->method('findAll')->with('foo')->willReturn([$firstResourceMock]);
        $this->assertSame([$firstResourceMock], $this->resourcesResolver->getResources($requestConfigurationMock, $repositoryMock));
    }

    function testUsesCustomRepositoryIfSpecified(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var RepositoryInterface|MockObject $customRepositoryMock */
        $customRepositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $firstResourceMock */
        $firstResourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn([$customRepositoryMock, 'findBy']);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryArguments')->willReturn([['foo' => true]]);
        $customRepositoryMock->expects($this->once())->method('findBy')->with(['foo' => true])->willReturn([$firstResourceMock]);
        $this->assertSame([$firstResourceMock], $this->resourcesResolver->getResources($requestConfigurationMock, $repositoryMock));
    }

    function testCreatesPaginatorByDefault(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var \Pagerfanta\Pagerfanta|MockObject $paginatorMock */
        $paginatorMock = $this->createMock(Pagerfanta::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('isPaginated')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('isFilterable')->willReturn(false);
        $requestConfigurationMock->expects($this->once())->method('isSortable')->willReturn(false);
        $repositoryMock->expects($this->once())->method('createPaginator')->with([], [])->willReturn($paginatorMock);
        $this->assertSame($paginatorMock, $this->resourcesResolver->getResources($requestConfigurationMock, $repositoryMock));
    }
}
