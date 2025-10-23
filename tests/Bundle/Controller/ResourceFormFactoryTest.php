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
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactory;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

final class ResourceFormFactoryTest extends TestCase
{
    /**
     * @var FormFactoryInterface|MockObject
     */
    private MockObject $formFactoryMock;
    private ResourceFormFactory $resourceFormFactory;
    protected function setUp(): void
    {
        $this->formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $this->resourceFormFactory = new ResourceFormFactory($this->formFactoryMock);
    }

    function testImplementsResourceFormFactoryInterface(): void
    {
        $this->assertInstanceOf(ResourceFormFactoryInterface::class, $this->resourceFormFactory);
    }

    function testCreatesAppropriateFormBasedOnConfiguration(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var FormInterface|MockObject $formMock */
        $formMock = $this->createMock(FormInterface::class);
        $requestConfigurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $requestConfigurationMock->expects($this->once())->method('getFormType')->willReturn('sylius_product_pricing');
        $requestConfigurationMock->expects($this->once())->method('getFormOptions')->willReturn([]);
        $this->formFactoryMock->expects($this->once())->method('create')->with('sylius_product_pricing', $resourceMock, $this->isType('array'))->willReturn($formMock);
        $this->assertSame($formMock, $this->resourceFormFactory->create($requestConfigurationMock, $resourceMock));
    }

    function testCreatesFormWithoutRootNameAndDisablesCsrfProtectionForNonHtmlRequests(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        /** @var FormInterface|MockObject $formMock */
        $formMock = $this->createMock(FormInterface::class);
        $requestConfigurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);
        $requestConfigurationMock->expects($this->once())->method('getFormType')->willReturn('sylius_product_api');
        $requestConfigurationMock->expects($this->once())->method('getFormOptions')->willReturn([]);
        $this->formFactoryMock->expects($this->once())->method('createNamed')->with('', 'sylius_product_api', $resourceMock, ['csrf_protection' => false])->willReturn($formMock);
        $this->assertSame($formMock, $this->resourceFormFactory->create($requestConfigurationMock, $resourceMock));
    }
}
