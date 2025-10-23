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
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Model\ResourceInterface;

final class NewResourceFactoryTest extends TestCase
{
    private NewResourceFactory $newResourceFactory;
    protected function setUp(): void
    {
        $this->newResourceFactory = new NewResourceFactory();
    }
    function testImplementsNewResourceFactoryInterface(): void
    {
        $this->assertInstanceOf(NewResourceFactoryInterface::class, $this->newResourceFactory);
    }

    function testCallsCreateNewByDefaultIfNoCustomMethodConfigured(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var FactoryInterface|MockObject $factoryMock */
        $factoryMock = $this->createMock(FactoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getFactoryMethod')->willReturn(null);
        $factoryMock->expects($this->once())->method('createNew')->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->newResourceFactory->create($requestConfigurationMock, $factoryMock));
    }

    function testCallsProperFactoryMethodsBasedOnConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var FactoryInterface|MockObject $factoryMock */
        $factoryMock = $this->createMock(FactoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getFactoryMethod')->willReturn('createNew');
        $requestConfigurationMock->expects($this->once())->method('getFactoryArguments')->willReturn(['00032']);
        $factoryMock->expects($this->once())->method('createNew')->with('00032')->willReturn($resourceMock);
        $this->assertSame($resourceMock, $this->newResourceFactory->create($requestConfigurationMock, $factoryMock));
    }

    function testCallsProperServiceBasedOnConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var FactoryInterface|MockObject $factoryMock */
        $factoryMock = $this->createMock(FactoryInterface::class);
        /** @var FactoryInterface|MockObject $customFactoryMock */
        $customFactoryMock = $this->createMock(FactoryInterface::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $requestConfigurationMock->expects($this->once())->method('getFactoryMethod')->willReturn([$customFactoryMock, 'createNew']);
        $requestConfigurationMock->expects($this->once())->method('getFactoryArguments')->willReturn(['foo', 'bar']);
        $customFactoryMock->expects($this->once())->method('createNew')->with('foo', 'bar')->willReturn($resourceMock);
        $factoryMock->expects($this->never())->method('createNew');
        $this->assertSame($resourceMock, $this->newResourceFactory->create($requestConfigurationMock, $factoryMock));
    }
}
