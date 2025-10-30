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
    private RegistryInterface|MockObject $resourceRegistryMock;

    private RequestConfigurationFactoryInterface|MockObject $requestConfigurationFactoryMock;

    private RequestContextInitiatorInterface|MockObject $decoratedMock;

    private LegacyRequestContextInitiator $legacyRequestContextInitiator;

    private Request|MockObject $requestMock;

    private MetadataInterface|MockObject $metadataMock;

    private RequestConfiguration|MockObject $requestConfigurationMock;

    protected function setUp(): void
    {
        $this->resourceRegistryMock = $this->createMock(RegistryInterface::class);
        $this->requestConfigurationFactoryMock = $this->createMock(RequestConfigurationFactoryInterface::class);
        $this->decoratedMock = $this->createMock(RequestContextInitiatorInterface::class);
        $this->legacyRequestContextInitiator = new LegacyRequestContextInitiator(
            $this->resourceRegistryMock,
            $this->requestConfigurationFactoryMock,
            $this->decoratedMock,
        );
        $this->requestMock = $this->createMock(Request::class);
        $this->metadataMock = $this->createMock(MetadataInterface::class);
        $this->requestConfigurationMock = $this->createMock(RequestConfiguration::class);
    }

    public function testAddsMetadataAndRequestConfigurationToTheContext(): void
    {
        $parameterBag = new ParameterBag(['_sylius' => ['resource' => 'app.dummy']]);

        $this->requestConfigurationMock->expects($this->once())->method('getParameters')->willReturn($parameterBag);
        $this->requestConfigurationMock->expects($this->once())->method('getVars')->willReturn([]);

        $this->requestMock->attributes = $parameterBag;

        $this->decoratedMock
            ->expects($this->once())
            ->method('initializeContext')
            ->with($this->requestMock)
            ->willReturn(new Context())
        ;

        $this->resourceRegistryMock
            ->expects($this->once())
            ->method('get')
            ->with('app.dummy')
            ->willReturn($this->metadataMock)
        ;

        $this->requestConfigurationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->metadataMock, $this->requestMock)
            ->willReturn($this->requestConfigurationMock)
        ;

        $result = $this->legacyRequestContextInitiator->initializeContext($this->requestMock);

        $this->assertInstanceOf(Context::class, $result);
        $this->assertSame($this->metadataMock, $result->get(MetadataOption::class)?->metadata());
        $this->assertSame(
            $this->requestConfigurationMock,
            $result->get(RequestConfigurationOption::class)?->requestConfiguration(),
        );
    }

    public function testDirectlyReturnsTheContextWhenRequestHasNoSyliusAttributes(): void
    {
        $this->requestMock->attributes = new ParameterBag();

        $this->decoratedMock
            ->expects($this->once())
            ->method('initializeContext')
            ->with($this->requestMock)
            ->willReturn(new Context())
        ;

        $this->resourceRegistryMock
            ->expects($this->never())
            ->method('get')
            ->with('app.dummy')
            ->willReturn($this->metadataMock)
        ;

        $this->requestConfigurationFactoryMock
            ->expects($this->never())
            ->method('create')
            ->with($this->metadataMock, $this->requestMock)
            ->willReturn($this->requestConfigurationMock)
        ;

        $result = $this->legacyRequestContextInitiator->initializeContext($this->requestMock);

        $this->assertInstanceOf(Context::class, $result);
        $this->assertNull($result->get(MetadataOption::class));
        $this->assertNull($result->get(RequestConfigurationOption::class));
    }

    public function testDirectlyReturnsTheContextWhenRequestHasNoResourceOnAttributes(): void
    {
        $this->requestMock->attributes = new ParameterBag(['_sylius' => ['section' => 'admin']]);

        $this->decoratedMock
            ->expects($this->once())
            ->method('initializeContext')
            ->with($this->requestMock)
            ->willReturn(new Context())
        ;

        $this->resourceRegistryMock
            ->expects($this->never())
            ->method('get')
            ->with('app.dummy')
            ->willReturn($this->metadataMock)
        ;

        $this->requestConfigurationFactoryMock
            ->expects($this->never())
            ->method('create')
            ->with($this->metadataMock, $this->requestMock)
            ->willReturn($this->requestConfigurationMock)
        ;

        $result = $this->legacyRequestContextInitiator->initializeContext($this->requestMock);

        $this->assertInstanceOf(Context::class, $result);
        $this->assertNull($result->get(MetadataOption::class));
        $this->assertNull($result->get(RequestConfigurationOption::class));
    }
}
