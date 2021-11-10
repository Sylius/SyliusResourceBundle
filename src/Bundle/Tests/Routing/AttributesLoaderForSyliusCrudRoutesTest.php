<?php

declare(strict_types=1);

namespace Routing;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AttributesLoaderForSyliusCrudRoutesTest extends KernelTestCase
{
    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_alias(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        // Test index
        $bookIndex = $routesCollection->get('app_book_index');
        $this->assertNotNull($bookIndex);
        $this->assertEquals('/books/', $bookIndex->getPath());
        $this->assertEquals(['GET'], $bookIndex->getMethods());
        $this->assertEquals([
            '_controller' => 'app.controller.book:indexAction',
            '_sylius' => [
                'permission' => false,
            ],
        ], $bookIndex->getDefaults());

        // Test create
        $bookCreate = $routesCollection->get('app_book_create');
        $this->assertNotNull($bookCreate);
        $this->assertEquals('/books/new', $bookCreate->getPath());
        $this->assertEquals(['GET', 'POST'], $bookCreate->getMethods());
        $this->assertEquals([
            '_controller' => 'app.controller.book:createAction',
            '_sylius' => [
                'permission' => false,
            ],
        ], $bookCreate->getDefaults());

        // Test show
        $bookShow = $routesCollection->get('app_book_show');
        $this->assertNotNull($bookShow);
        $this->assertEquals('/books/{id}', $bookShow->getPath());
        $this->assertEquals(['GET'], $bookShow->getMethods());
        $this->assertEquals([
            '_controller' => 'app.controller.book:showAction',
            '_sylius' => [
                'permission' => false,
            ],
        ], $bookShow->getDefaults());

        // Test update
        $bookUpdate = $routesCollection->get('app_book_update');
        $this->assertNotNull($bookUpdate);
        $this->assertEquals(['GET', 'PUT', 'PATCH'], $bookUpdate->getMethods());
        $this->assertEquals('/books/{id}/edit', $bookUpdate->getPath());
        $this->assertEquals([
            '_controller' => 'app.controller.book:updateAction',
            '_sylius' => [
                'permission' => false,
            ],
        ], $bookUpdate->getDefaults());

        // Test delete
        $bookDelete = $routesCollection->get('app_book_delete');
        $this->assertNotNull($bookDelete);
        $this->assertEquals('/books/{id}', $bookDelete->getPath());
        $this->assertEquals(['DELETE'], $bookDelete->getMethods());
        $this->assertEquals([
            '_controller' => 'app.controller.book:deleteAction',
            '_sylius' => [
                'permission' => false,
            ],
        ], $bookDelete->getDefaults());
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_section(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        // Test index
        $bookIndex = $routesCollection->get('app_admin_book_index');
        $this->assertNotNull($bookIndex);

        // Test create
        $bookCreate = $routesCollection->get('app_admin_book_create');
        $this->assertNotNull($bookCreate);

        // Test show
        $bookShow = $routesCollection->get('app_admin_book_show');
        $this->assertNotNull($bookShow);

        // Test update
        $bookUpdate = $routesCollection->get('app_admin_book_update');
        $this->assertNotNull($bookUpdate);

        // Test delete
        $bookDelete = $routesCollection->get('app_admin_book_delete');
        $this->assertNotNull($bookDelete);
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_criteria(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'criteria';

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'criteria' => [
                'library' => '$libraryId',
            ],
        ], $bookIndex->getDefault('_sylius'));

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNotNull($bookCreate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'criteria' => [
                'library' => '$libraryId',
            ],
        ], $bookCreate->getDefault('_sylius'));

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNotNull($bookShow);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'criteria' => [
                'library' => '$libraryId',
            ],
        ], $bookShow->getDefault('_sylius'));

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'criteria' => [
                'library' => '$libraryId',
            ],
        ], $bookUpdate->getDefault('_sylius'));

        // Test delete
        $bookDelete = $routesCollection->get(sprintf('app_%s_book_delete', $section));
        $this->assertNotNull($bookDelete);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'criteria' => [
                'library' => '$libraryId',
            ],
        ], $bookDelete->getDefault('_sylius'));
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_templates(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'template';

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'template' => 'backend/crud/index.html.twig',
        ], $bookIndex->getDefault('_sylius'));

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNotNull($bookCreate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'template' => 'backend/crud/create.html.twig',
        ], $bookCreate->getDefault('_sylius'));

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNotNull($bookShow);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'template' => 'backend/crud/show.html.twig',
        ], $bookShow->getDefault('_sylius'));

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'template' => 'backend/crud/update.html.twig',
        ], $bookUpdate->getDefault('_sylius'));

        // Test delete
        $bookDelete = $routesCollection->get(sprintf('app_%s_book_delete', $section));
        $this->assertNotNull($bookDelete);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookDelete->getDefault('_sylius'));
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_grids(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'grid';

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'grid' => 'app_book',
        ], $bookIndex->getDefault('_sylius'));

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNotNull($bookCreate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookCreate->getDefault('_sylius'));

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNotNull($bookShow);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookShow->getDefault('_sylius'));

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookUpdate->getDefault('_sylius'));

        // Test delete
        $bookDelete = $routesCollection->get(sprintf('app_%s_book_delete', $section));
        $this->assertNotNull($bookDelete);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookDelete->getDefault('_sylius'));
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_vars(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'vars';

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'vars' => [
                'subheader' => 'app.ui.manage_your_books',
                'icon' => 'book',
            ],
        ], $bookIndex->getDefault('_sylius'));

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNotNull($bookCreate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'vars' => [
                'subheader' => 'app.ui.manage_your_books',
            ],
        ], $bookCreate->getDefault('_sylius'));

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNotNull($bookShow);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'vars' => [
                'subheader' => 'app.ui.manage_your_books',
            ],
        ], $bookShow->getDefault('_sylius'));

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'vars' => [
                'subheader' => 'app.ui.manage_your_books',
            ],
        ], $bookUpdate->getDefault('_sylius'));

        // Test delete
        $bookDelete = $routesCollection->get(sprintf('app_%s_book_delete', $section));
        $this->assertNotNull($bookDelete);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'vars' => [
                'subheader' => 'app.ui.manage_your_books',
            ],
        ], $bookDelete->getDefault('_sylius'));
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_redirect(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'redirect';

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookIndex->getDefault('_sylius'));

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNotNull($bookCreate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'redirect' => 'app_redirect_book_update',
        ], $bookCreate->getDefault('_sylius'));

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNotNull($bookShow);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookShow->getDefault('_sylius'));

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
            'redirect' => 'app_redirect_book_update'
        ], $bookUpdate->getDefault('_sylius'));

        // Test delete
        $bookDelete = $routesCollection->get(sprintf('app_%s_book_delete', $section));
        $this->assertNotNull($bookDelete);
        $this->assertEquals([
            'permission' => false,
            'section' => $section,
        ], $bookDelete->getDefault('_sylius'));
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_permission(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'permission';

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);
        $this->assertEquals([
            'permission' => true,
            'section' => $section,
        ], $bookIndex->getDefault('_sylius'));

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNotNull($bookCreate);
        $this->assertEquals([
            'permission' => true,
            'section' => $section,
        ], $bookCreate->getDefault('_sylius'));

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNotNull($bookShow);
        $this->assertEquals([
            'permission' => true,
            'section' => $section,
        ], $bookShow->getDefault('_sylius'));

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);
        $this->assertEquals([
            'permission' => true,
            'section' => $section,
        ], $bookUpdate->getDefault('_sylius'));

        // Test delete
        $bookDelete = $routesCollection->get('app_permission_book_delete');
        $this->assertNotNull($bookDelete);
        $this->assertEquals([
            'permission' => true,
            'section' => $section,
        ], $bookDelete->getDefault('_sylius'));
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_except(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'except';

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNull($bookCreate);

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNull($bookShow);

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);

        // Test delete
        $bookDelete = $routesCollection->get(sprintf('app_%s_book_delete', $section));
        $this->assertNotNull($bookDelete);
    }

    /**
     * @test
     */
    public function it_generates_crud_routes_from_resource_with_only(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            $this->markTestSkipped();
        }

        self::bootKernel(['environment' => 'test_with_attributes']);

        $container = static::$container;

        $attributesLoader = $container->get('sylius.routing.loader.attributes');

        $routesCollection = $attributesLoader->__invoke();

        $section = 'only';

        // Test index
        $bookIndex = $routesCollection->get(sprintf('app_%s_book_index', $section));
        $this->assertNotNull($bookIndex);

        // Test update
        $bookUpdate = $routesCollection->get(sprintf('app_%s_book_update', $section));
        $this->assertNotNull($bookUpdate);

        // Test show
        $bookShow = $routesCollection->get(sprintf('app_%s_book_show', $section));
        $this->assertNull($bookShow);

        // Test delete
        $bookDelete = $routesCollection->get(sprintf('app_%s_book_delete', $section));
        $this->assertNull($bookDelete);

        // Test create
        $bookCreate = $routesCollection->get(sprintf('app_%s_book_create', $section));
        $this->assertNull($bookCreate);
    }
}
