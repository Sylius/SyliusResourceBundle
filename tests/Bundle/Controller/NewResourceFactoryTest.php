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
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory;
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

    public function testImplementsNewResourceFactoryInterface(): void
    {
        $this->assertInstanceOf(NewResourceFactoryInterface::class, $this->newResourceFactory);
    }

    public function testCallsCreateNewByDefaultIfNoCustomMethodConfigured(): void
    {
        $requestConfiguration = $this->createRequestConfiguration(null, []);
        $factory = $this->createMock(FactoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $factory
            ->expects($this->once())
            ->method('createNew')
            ->willReturn($resource);

        $result = $this->newResourceFactory->create($requestConfiguration, $factory);

        $this->assertSame($resource, $result);
    }

    public function testCallsProperFactoryMethodsBasedOnConfiguration(): void
    {
        $requestConfiguration = $this->createRequestConfiguration('createNew', ['00032']);
        $factory = $this->createMock(FactoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $factory
            ->expects($this->once())
            ->method('createNew')
            ->with('00032')
            ->willReturn($resource);

        $result = $this->newResourceFactory->create($requestConfiguration, $factory);

        $this->assertSame($resource, $result);
    }

    public function testCallsProperServiceBasedOnConfiguration(): void
    {
        $customFactory = $this->createMock(FactoryInterface::class);
        $requestConfiguration = $this->createRequestConfiguration([$customFactory, 'createNew'], ['foo', 'bar']);
        $factory = $this->createMock(FactoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $customFactory
            ->expects($this->once())
            ->method('createNew')
            ->with('foo', 'bar')
            ->willReturn($resource);

        $factory
            ->expects($this->never())
            ->method('createNew');

        $result = $this->newResourceFactory->create($requestConfiguration, $factory);

        $this->assertSame($resource, $result);
    }

    /**
     * @param string|array<object|string>|null $factoryMethod
     * @param array<mixed> $factoryArguments
     *
     * @return MockObject&RequestConfiguration
     */
    private function createRequestConfiguration(string|array|null $factoryMethod, array $factoryArguments): MockObject
    {
        /** @var MockObject&RequestConfiguration $requestConfiguration */
        $requestConfiguration = $this->createMock(RequestConfiguration::class);
        $requestConfiguration->method('getFactoryMethod')->willReturn($factoryMethod);
        $requestConfiguration->method('getFactoryArguments')->willReturn($factoryArguments);

        return $requestConfiguration;
    }
}
