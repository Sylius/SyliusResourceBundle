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
use Sylius\Bundle\ResourceBundle\Controller\ViewHandler;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

final class ViewHandlerTest extends TestCase
{
    /**
     * @var ConfigurableViewHandlerInterface|MockObject
     */
    private MockObject $restViewHandlerMock;
    private ViewHandler $viewHandler;
    protected function setUp(): void
    {
        $this->restViewHandlerMock = $this->createMock(ConfigurableViewHandlerInterface::class);
        $this->viewHandler = new ViewHandler($this->restViewHandlerMock);
    }

    function testImplementsViewHandlerInterface(): void
    {
        $this->assertInstanceOf(ViewHandlerInterface::class, $this->viewHandler);
    }

    function testHandlesViewNormallyForHtmlRequests(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);
        $requestConfigurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(true);
        $view = View::create();
        $this->restViewHandlerMock->expects($this->once())->method('handle')->with($view)->willReturn($responseMock);
        $this->assertSame($responseMock, $this->viewHandler->handle($requestConfigurationMock, $view));
    }

    function testSetsProperValuesForNonHtmlRequests(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var Response|MockObject $responseMock */
        $responseMock = $this->createMock(Response::class);
        $requestConfigurationMock->expects($this->once())->method('isHtmlRequest')->willReturn(false);
        $view = View::create();
        $view->setContext(new Context());
        $requestConfigurationMock->expects($this->once())->method('getSerializationGroups')->willReturn(['Detailed']);
        $requestConfigurationMock->expects($this->once())->method('getSerializationVersion')->willReturn('2.0.0');
        $this->restViewHandlerMock->expects($this->once())->method('setExclusionStrategyGroups')->with(['Detailed']);
        $this->restViewHandlerMock->expects($this->once())->method('setExclusionStrategyVersion')->with('2.0.0');
        $this->restViewHandlerMock->expects($this->once())->method('handle')->with($view)->willReturn($responseMock);
        $this->assertSame($responseMock, $this->viewHandler->handle($requestConfigurationMock, $view));
    }
}
