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
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandler;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandlerTest extends TestCase
{
    private RouterInterface&MockObject $router;

    private RedirectHandler $redirectHandler;

    private RequestConfiguration&MockObject $configuration;

    private ResourceInterface&MockObject $resource;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->redirectHandler = new RedirectHandler($this->router);
        $this->configuration = $this->createMock(RequestConfiguration::class);
        $this->resource = $this->createMock(ResourceInterface::class);
    }

    public function test_it_redirects_to_show_route(): void
    {
        $this->configuration
            ->method('getRedirectRoute')
            ->with('show')
            ->willReturn('app_resource_show');

        $this->configuration
            ->method('getRedirectParameters')
            ->willReturn(['id' => 1]);

        $this->router
            ->method('generate')
            ->with('app_resource_show', ['id' => 1])
            ->willReturn('/resource/1');

        $response = $this->redirectHandler->redirectToResource($this->configuration, $this->resource);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/resource/1', $response->getTargetUrl());
    }

    public function test_it_falls_back_to_index_if_show_route_is_not_found(): void
    {
        $this->configuration
            ->method('getRedirectRoute')
            ->willReturnMap([
                ['show', 'app_resource_show'],
                ['index', 'app_resource_index'],
            ]);

        $this->configuration
            ->method('getRedirectParameters')
            ->willReturn([]);

        $this->router
            ->method('generate')
            ->willReturnCallback(function (string $route) {
                if ($route === 'app_resource_show') {
                    throw new RouteNotFoundException();
                }

                return '/resource/';
            });

        $response = $this->redirectHandler->redirectToResource($this->configuration, $this->resource);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/resource/', $response->getTargetUrl());
    }

    public function test_it_redirects_to_index(): void
    {
        $this->configuration
            ->method('getRedirectRoute')
            ->with('index')
            ->willReturn('app_resource_index');

        $this->configuration
            ->method('getRedirectParameters')
            ->willReturn([]);

        $this->router
            ->method('generate')
            ->with('app_resource_index', [])
            ->willReturn('/resource/');

        $response = $this->redirectHandler->redirectToIndex($this->configuration);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/resource/', $response->getTargetUrl());
    }

    public function test_it_redirects_to_referer(): void
    {
        $this->configuration
            ->method('getRedirectReferer')
            ->willReturn('/previous-page');

        $response = $this->redirectHandler->redirectToRoute($this->configuration, 'referer');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/previous-page', $response->getTargetUrl());
    }

    public function test_it_returns_header_redirection(): void
    {
        $this->configuration
            ->method('isHeaderRedirection')
            ->willReturn(true);

        $this->configuration
            ->method('getRedirectHash')
            ->willReturn('#hash');

        $response = $this->redirectHandler->redirect($this->configuration, '/resource/1');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('', $response->getContent());
        $this->assertEquals('/resource/1#hash', $response->headers->get('X-SYLIUS-LOCATION'));
    }

    public function test_it_returns_standard_redirect_response(): void
    {
        $this->configuration
            ->method('isHeaderRedirection')
            ->willReturn(false);

        $this->configuration
            ->method('getRedirectHash')
            ->willReturn('#hash');

        $response = $this->redirectHandler->redirect($this->configuration, '/resource/1');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/resource/1#hash', $response->getTargetUrl());
    }
}
