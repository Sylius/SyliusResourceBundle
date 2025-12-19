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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProvider;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class SingleResourceProviderTest extends TestCase
{
    private SingleResourceProvider $singleResourceProvider;

    protected function setUp(): void
    {
        $this->singleResourceProvider = new SingleResourceProvider();
    }

    public function testImplementsSingleResourceProviderInterface(): void
    {
        $this->assertInstanceOf(SingleResourceProviderInterface::class, $this->singleResourceProvider);
    }

    public function testLooksForSpecificResourceWithIdByDefault(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var ParameterBag|MockObject $requestAttributesMock */
        $requestAttributesMock = $this->createMock(ParameterBag::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->attributes = $requestAttributesMock;
        $requestAttributesMock->method('has')->with('id')->willReturn(true);
        $requestAttributesMock->expects($this->once())->method('get')->with('id')->willReturn(5);
        $repositoryMock->expects($this->once())->method('find')->with(5)->willReturn(null);
        $this->assertNull($this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testCanFindSpecificResourceWithIdByDefault(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var ParameterBag|MockObject $requestAttributesMock */
        $requestAttributesMock = $this->createMock(ParameterBag::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->attributes = $requestAttributesMock;
        $requestAttributesMock->method('has')->with('id')->willReturn(true);
        $requestAttributesMock->expects($this->once())->method('get')->with('id')->willReturn(3);
        $repositoryMock->expects($this->once())->method('find')->with(3)->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testCanFindSpecificResourceWithSlugByDefault(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var ParameterBag|MockObject $requestAttributesMock */
        $requestAttributesMock = $this->createMock(ParameterBag::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getCriteria')->willReturn([]);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->attributes = $requestAttributesMock;
        $requestAttributesMock->expects($this->exactly(2))->method('has')->willReturnMap([['id', false], ['slug', true]]);
        $requestAttributesMock->expects($this->once())->method('get')->with('slug')->willReturn('the-most-awesome-hat');
        $repositoryMock->expects($this->once())->method('findOneBy')->with(['slug' => 'the-most-awesome-hat'])->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testCanFindSpecificResourceWithCustomCriteria(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var ParameterBag|MockObject $requestAttributesMock */
        $requestAttributesMock = $this->createMock(ParameterBag::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getCriteria')->willReturn(['request-configuration-criteria' => '1']);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->attributes = $requestAttributesMock;
        $requestAttributesMock->expects($this->exactly(2))->method('has')->willReturnMap([['id', false], ['slug', false]]);
        $repositoryMock->expects($this->once())->method('findOneBy')->with(['request-configuration-criteria' => '1'])->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testCanFindSpecificResourceWithMergedCustomCriteria(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var ParameterBag|MockObject $requestAttributesMock */
        $requestAttributesMock = $this->createMock(ParameterBag::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getCriteria')->willReturn(['request-configuration-criteria' => '1']);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->attributes = $requestAttributesMock;
        $requestAttributesMock->expects($this->exactly(2))->method('has')->willReturnMap([['id', false], ['slug', true]]);
        $requestAttributesMock->expects($this->once())->method('get')->with('slug')->willReturn('banana');
        $repositoryMock->expects($this->once())->method('findOneBy')->with(['slug' => 'banana', 'request-configuration-criteria' => '1'])->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testCanFindSpecificResourceWithMergedCustomCriteriaOverwritingTheAttributes(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var ParameterBag|MockObject $requestAttributesMock */
        $requestAttributesMock = $this->createMock(ParameterBag::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getCriteria')->willReturn(['id' => 5]);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn(null);
        $requestConfigurationMock->expects($this->once())->method('getRequest')->willReturn($requestMock);
        $requestMock->attributes = $requestAttributesMock;
        $requestAttributesMock->expects($this->exactly(2))->method('has')->willReturnMap([['id', false], ['slug', false]]);
        $repositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 5])->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testUsesACustomMethodIfConfigured(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn('find');
        $requestConfigurationMock->expects($this->once())->method('getRepositoryArguments')->willReturn(['foo']);
        $repositoryMock->expects($this->once())->method('find')->with('foo')->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }

    public function testUsesACustomRepositoryIfConfigured(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var RepositoryInterface|MockObject $repositoryMock */
        $repositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var RepositoryInterface|MockObject $customRepositoryMock */
        $customRepositoryMock = $this->createMock(RepositoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryMethod')->willReturn([$customRepositoryMock, 'find']);
        $requestConfigurationMock->expects($this->once())->method('getRepositoryArguments')->willReturn(['foo']);
        $customRepositoryMock->expects($this->once())->method('find')->with('foo')->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->singleResourceProvider->get($requestConfigurationMock, $repositoryMock));
    }
}
