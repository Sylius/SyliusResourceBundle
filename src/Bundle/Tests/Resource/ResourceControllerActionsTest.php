<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Tests\Resource;

use Sylius\Bundle\ResourceBundle\Controller\Action\CreateAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\DeleteAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\IndexAction;
use Sylius\Bundle\ResourceBundle\Controller\Action\UpdateAction;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\RouteCollection;

final class ResourceControllerActionsTest extends WebTestCase
{
    /** @test */
    public function it_registers_resource_controller_by_default(): void
    {
        $client = parent::createClient();
        $container = $client->getContainer();

        $this->assertTrue($container->has('app.controller.comic_book'));
        $this->assertTrue($container->get('app.controller.comic_book') instanceof ResourceController);

        /** @var RouteCollection $routeCollection */
        $routeCollection = $container->get('router')->getRouteCollection();
        $indexRouteController = $routeCollection->get('app_comic_book_index')->getDefault('_controller');

        $this->assertStringContainsString('app.controller.comic_book', $indexRouteController);
        $this->assertStringNotContainsString('app.action.index.comic_book', $indexRouteController);
    }

    /** @test */
    public function it_registers_controller_actions_for_resource_instead_of_default_controller(): void
    {
        $client = parent::createClient();
        $container = $client->getContainer();

        $this->assertTrue($container->get('app.action.create.poetry_book') instanceof CreateAction);
        $this->assertTrue($container->get('app.action.update.poetry_book') instanceof UpdateAction);
        $this->assertTrue($container->get('app.action.index.poetry_book') instanceof IndexAction);
        $this->assertTrue($container->get('app.action.delete.poetry_book') instanceof DeleteAction);

        /** @var RouteCollection $routeCollection */
        $routeCollection = $container->get('router')->getRouteCollection();

        $indexRouteController = $routeCollection->get('app_poetry_book_index')->getDefault('_controller');
        $this->assertStringNotContainsString('app.controller.poetry_book', $indexRouteController);
        $this->assertStringContainsString('app.action.index.poetry_book', $indexRouteController);

        $createRouteController = $routeCollection->get('app_poetry_book_create')->getDefault('_controller');
        $this->assertStringNotContainsString('app.controller.poetry_book', $createRouteController);
        $this->assertStringContainsString('app.action.create.poetry_book', $createRouteController);

        $updateRouteController = $routeCollection->get('app_poetry_book_update')->getDefault('_controller');
        $this->assertStringNotContainsString('app.controller.poetry_book', $updateRouteController);
        $this->assertStringContainsString('app.action.update.poetry_book', $updateRouteController);

        $deleteRouteController = $routeCollection->get('app_poetry_book_delete')->getDefault('_controller');
        $this->assertStringNotContainsString('app.controller.poetry_book', $deleteRouteController);
        $this->assertStringContainsString('app.action.delete.poetry_book', $deleteRouteController);

        $showRouteController = $routeCollection->get('app_poetry_book_show')->getDefault('_controller');
        $this->assertStringNotContainsString('app.controller.poetry_book', $showRouteController);
        $this->assertStringContainsString('app.action.show.poetry_book', $showRouteController);
    }
}
