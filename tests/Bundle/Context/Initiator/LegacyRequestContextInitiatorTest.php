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

namespace Sylius\Bundle\ResourceBundle\Tests\Context\Initiator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Context\Initiator\LegacyRequestContextInitiator;
use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Initiator\RequestContextInitiatorInterface;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

final class LegacyRequestContextInitiatorTest extends TestCase
{
    /**
     * @var RegistryInterface|MockObject
     */
    private MockObject $resourceRegistryMock;
    /**
     * @var RequestConfigurationFactoryInterface|MockObject
     */
    private MockObject $requestConfigurationFactoryMock;
    /**
     * @var RequestContextInitiatorInterface|MockObject
     */
    private MockObject $decoratedMock;
    private LegacyRequestContextInitiator $legacyRequestContextInitiator;
    protected function setUp(): void
    {
        $this->resourceRegistryMock = $this->createMock(RegistryInterface::class);
        $this->requestConfigurationFactoryMock = $this->createMock(RequestConfigurationFactoryInterface::class);
        $this->decoratedMock = $this->createMock(RequestContextInitiatorInterface::class);
        $this->legacyRequestContextInitiator = new LegacyRequestContextInitiator($this->resourceRegistryMock, $this->requestConfigurationFactoryMock, $this->decoratedMock);
    }

    function testAddsMetadataAndRequestConfigurationToTheContext(): void
    {
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $parameterBag = new ParameterBag(['_sylius' => ['resource' => 'app.dummy']]);
        $requestConfigurationMock->expects($this->once())->method('getParameters')->willReturn($parameterBag);
        $requestConfigurationMock->expects($this->once())->method('getVars')->willReturn([]);
        $requestMock->attributes = $parameterBag;
        $this->decoratedMock->expects($this->once())->method('initializeContext')->with($requestMock)->willReturn(new Context());
        $this->resourceRegistryMock->expects($this->once())->method('get')->with('app.dummy')->willReturn($metadataMock);
        $this->requestConfigurationFactoryMock->expects($this->once())->method('create')->with($metadataMock, $requestMock)->willReturn($requestConfigurationMock);
        $result = $this->legacyRequestContextInitiator->initializeContext($requestMock);
        $this->assertInstanceOf(Context::class, $result);
        $this->assertSame($metadataMock, $result->get(MetadataOption::class)?->metadata());
        $this->assertSame($requestConfigurationMock, $result->get(RequestConfigurationOption::class)?->requestConfiguration());
    }

    function testDirectlyReturnsTheContextWhenRequestHasNoSyliusAttributes(): void
    {
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $requestMock->attributes = new ParameterBag();
        $this->decoratedMock->expects($this->once())->method('initializeContext')->with($requestMock)->willReturn(new Context());
        $this->resourceRegistryMock->expects($this->never())->method('get')->with('app.dummy')->willReturn($metadataMock);
        $this->requestConfigurationFactoryMock->expects($this->never())->method('create')->with($metadataMock, $requestMock)->willReturn($requestConfigurationMock);
        $result = $this->legacyRequestContextInitiator->initializeContext($requestMock);
        $this->assertInstanceOf(Context::class, $result);
        $this->assertNull($result->get(MetadataOption::class));
        $this->assertNull($result->get(RequestConfigurationOption::class));
    }

    function testDirectlyReturnsTheContextWhenRequestHasNoResourceOnAttributes(): void
    {
        /** @var Request|MockObject $requestMock */
        $requestMock = $this->createMock(Request::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $requestMock->attributes = new ParameterBag(['_sylius' => ['section' => 'admin']]);
        $this->decoratedMock->expects($this->once())->method('initializeContext')->with($requestMock)->willReturn(new Context());
        $this->resourceRegistryMock->expects($this->never())->method('get')->with('app.dummy')->willReturn($metadataMock);
        $this->requestConfigurationFactoryMock->expects($this->never())->method('create')->with($metadataMock, $requestMock)->willReturn($requestConfigurationMock);
        $result = $this->legacyRequestContextInitiator->initializeContext($requestMock);
        $this->assertInstanceOf(Context::class, $result);
        $this->assertNull($result->get(MetadataOption::class));
        $this->assertNull($result->get(RequestConfigurationOption::class));
    }
}
