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
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactory;
use Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class RequestConfigurationFactoryTest extends TestCase
{
    /**
     * @var ParametersParserInterface|MockObject
     */
    private MockObject $parametersParserMock;
    private RequestConfigurationFactory $requestConfigurationFactory;
    protected function setUp(): void
    {
        $this->parametersParserMock = $this->createMock(ParametersParserInterface::class);
        $this->requestConfigurationFactory = new RequestConfigurationFactory($this->parametersParserMock, RequestConfiguration::class);
    }

    function testImplementsRequestConfigurationFactoryInterface(): void
    {
        $this->assertInstanceOf(RequestConfigurationFactoryInterface::class, $this->requestConfigurationFactory);
    }

    function testCreatesConfigurationFromResourceMetadataAndRequest(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn([]);
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn(['template' => ':Product:show.html.twig']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with(['template' => ':Product:show.html.twig'], $requestMock)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;
        $this->assertInstanceOf(RequestConfiguration::class, $this->requestConfigurationFactory->create($metadataMock, $requestMock));
    }

    function testCreatesConfigurationWithoutDefaultSettings(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn([]);
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn(['template' => ':Product:list.html.twig']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with(['template' => ':Product:list.html.twig'], $requestMock)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;
        $this->assertFalse($this->requestConfigurationFactory->create($metadataMock, $requestMock)->isSortable());
    }

    function testCreatesConfigurationForSerializationGroupFromSingleHeader(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
        ]);
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn(['groups=Default,Detailed']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with([
            'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
            'serialization_groups' => ['Default', 'Detailed'],
        ], $requestMock)
            ->willReturn(['serialization_groups' => ['Default', 'Detailed']])
        ;
        $this->assertSame(['Default', 'Detailed'], $this->requestConfigurationFactory->create($metadataMock, $requestMock)->getSerializationGroups());
    }

    function testCreatesConfigurationForSerializationGroupFromMultipleHeaders(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
        ]);
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn(['application/json', 'groups=Default,Detailed']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with([
            'allowed_serialization_groups' => ['Default', 'Detailed', 'Other'],
            'serialization_groups' => ['Default', 'Detailed'],
        ], $requestMock)
            ->willReturn(['serialization_groups' => ['Default', 'Detailed']])
        ;
        $this->assertSame(['Default', 'Detailed'], $this->requestConfigurationFactory->create($metadataMock, $requestMock)->getSerializationGroups());
    }

    function testCreatesConfigurationUsingOnlyThoseSerializationGroupsThatAreAllowed(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default'],
        ]);
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn(['application/json', 'groups=Default,Detailed']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with([
            'allowed_serialization_groups' => ['Default'],
            'serialization_groups' => ['Default'],
        ], $requestMock)
            ->willReturn(['serialization_groups' => ['Default']])
        ;
        $this->assertSame(['Default'], $this->requestConfigurationFactory->create($metadataMock, $requestMock)->getSerializationGroups());
    }

    function testCreatesConfigurationUsingOnlyThoseSerializationGroupsThatAreAllowedOrDefinedAsDefault(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn([
            'allowed_serialization_groups' => ['Default'],
            'serialization_groups' => ['Detailed'],
        ]);
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn(['application/json', 'groups=Default,Detailed,Other']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with([
            'allowed_serialization_groups' => ['Default'],
            'serialization_groups' => ['Default', 'Detailed'],
        ], $requestMock)
            ->willReturn(['serialization_groups' => ['Default', 'Detailed']])
        ;
        $this->assertSame(['Default', 'Detailed'], $this->requestConfigurationFactory->create($metadataMock, $requestMock)->getSerializationGroups());
    }

    function testCreatesConfigurationUsingOnlyThoseSerializationGroupsThatAreDefinedAsDefault(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn([
            'serialization_groups' => ['Detailed'],
        ]);
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn(['application/json', 'groups=Default,Detailed,Other']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with(['serialization_groups' => ['Detailed']], $requestMock)
            ->willReturn(['serialization_groups' => ['Detailed']])
        ;
        $this->assertSame(['Detailed'], $this->requestConfigurationFactory->create($metadataMock, $requestMock)->getSerializationGroups());
    }

    function testCreatesConfigurationForSerializationVersionFromSingleHeader(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn(['version=1.0.0']);
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn([]);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with(['serialization_version' => '1.0.0'], $requestMock)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;
        $this->assertFalse($this->requestConfigurationFactory->create($metadataMock, $requestMock)->isSortable());
    }

    function testCreatesConfigurationForSerializationVersionFromMultipleHeaders(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn(['application/xml', 'version=1.0.0']);
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn([]);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with(['serialization_version' => '1.0.0'], $requestMock)
            ->willReturn(['template' => ':Product:list.html.twig'])
        ;
        $this->assertFalse($this->requestConfigurationFactory->create($metadataMock, $requestMock)->isSortable());
    }

    function testCreatesConfigurationWithDefaultSettings(): void
    {
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var HeaderBag|MockObject $headersBagMock */
        $headersBagMock = $this->createMock(HeaderBag::class);
        /** @var ParameterBag|MockObject $attributesBagMock */
        $attributesBagMock = $this->createMock(ParameterBag::class);
        $this->requestConfigurationFactory = new RequestConfigurationFactory($this->parametersParserMock, RequestConfiguration::class, ['sortable' => true]);
        $requestMock->headers = $headersBagMock;
        $requestMock->attributes = $attributesBagMock;
        $headersBagMock->expects($this->any())->method('all')->with('Accept')->willReturn([]);
        $attributesBagMock->expects($this->once())->method('get')->with('_sylius', [])->willReturn(['template' => ':Product:list.html.twig']);
        $this->parametersParserMock->expects($this->once())->method('parseRequestValues')->with(['template' => ':Product:list.html.twig', 'sortable' => true], $requestMock)
            ->willReturn(['template' => ':Product:list.html.twig', 'sortable' => true])
        ;
        $this->assertTrue($this->requestConfigurationFactory->create($metadataMock, $requestMock)->isSortable());
    }
}
