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

namespace Sylius\Bundle\ResourceBundle\Tests\Routing;

use Behat\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Routing\ResourceLoader;
use Sylius\Bundle\ResourceBundle\Routing\RouteFactoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class ResourceLoaderTest extends TestCase
{
    public function testItIsASymfonyRoutingLoader(): void
    {
        $loader = $this->createLoader();

        $this->assertInstanceOf(LoaderInterface::class, $loader);
    }

    public function testItThrowsExceptionWhenBothOnlyAndExceptOptionsAreConfigured(): void
    {
        $loader = $this->createLoader();

        $resource = <<<YAML
alias: sylius.product
except: ['show', 'delete']
only: ['create']
YAML;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You can configure only one of "except" & "only" options.');

        $loader->load($resource, 'sylius.resource');
    }

    public function testItSupportsSyliusResourceType(): void
    {
        $loader = $this->createLoader();

        $this->assertTrue($loader->supports('resource', 'sylius.resource'));
    }

    public function testItSupportsSyliusResourceApiType(): void
    {
        $loader = $this->createLoader();

        $this->assertTrue($loader->supports('resource', 'sylius.resource_api'));
    }

    public function testItDoesNotSupportOtherTypes(): void
    {
        $loader = $this->createLoader();

        $this->assertFalse($loader->supports('resource', 'other_type'));
    }

    public function testItGeneratesRoutingBasedOnResourceConfiguration(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $this->assertInstanceOf(RouteCollection::class, $result);
        $this->assertNotNull($result->get('sylius_product_index'));
        $this->assertNotNull($result->get('sylius_product_show'));
        $this->assertNotNull($result->get('sylius_product_create'));
        $this->assertNotNull($result->get('sylius_product_update'));
        $this->assertNotNull($result->get('sylius_product_delete'));
        $this->assertNotNull($result->get('sylius_product_bulk_delete'));
    }

    public function testItIncludesOnlySpecificRoutesIfConfigured(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
only: ['create', 'index']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $this->assertNotNull($result->get('sylius_product_index'));
        $this->assertNotNull($result->get('sylius_product_create'));
        $this->assertNull($result->get('sylius_product_show'));
        $this->assertNull($result->get('sylius_product_update'));
        $this->assertNull($result->get('sylius_product_delete'));
        $this->assertNull($result->get('sylius_product_bulk_delete'));
    }

    public function testItExcludesSpecificRoutesIfConfigured(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
except: ['show', 'delete', 'bulkDelete']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $this->assertNotNull($result->get('sylius_product_index'));
        $this->assertNotNull($result->get('sylius_product_create'));
        $this->assertNotNull($result->get('sylius_product_update'));
        $this->assertNull($result->get('sylius_product_show'));
        $this->assertNull($result->get('sylius_product_delete'));
        $this->assertNull($result->get('sylius_product_bulk_delete'));
    }

    public function testItGeneratesApiRoutingWithoutBulkDelete(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
YAML;

        $result = $loader->load($resource, 'sylius.resource_api');

        $this->assertNotNull($result->get('sylius_product_index'));
        $this->assertNotNull($result->get('sylius_product_show'));
        $this->assertNotNull($result->get('sylius_product_create'));
        $this->assertNotNull($result->get('sylius_product_update'));
        $this->assertNotNull($result->get('sylius_product_delete'));
        $this->assertNull($result->get('sylius_product_bulk_delete'));
    }

    public function testItGeneratesRoutingWithCustomFormConfiguration(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
form: sylius_product_custom
only: ['create', 'update']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $this->assertRouteHasSyliusOption($result->get('sylius_product_create'), 'form', 'sylius_product_custom');
        $this->assertRouteHasSyliusOption($result->get('sylius_product_update'), 'form', 'sylius_product_custom');
    }

    public function testItGeneratesRoutingWithSerializationVersion(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
serialization_version: 1.0
only: ['index', 'show']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $this->assertRouteHasSyliusOption($result->get('sylius_product_index'), 'serialization_version', '1.0');
        $this->assertRouteHasSyliusOption($result->get('sylius_product_show'), 'serialization_version', '1.0');
    }

    public function testItGeneratesRoutingWithFilterableOption(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
filterable: true
only: ['index']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $this->assertRouteHasSyliusOption($result->get('sylius_product_index'), 'filterable', true);
    }

    public function testItGeneratesRoutingWithFilterableFalse(): void
    {
        $loader = $this->createLoaderWithMockedDependencies();

        $resource = <<<YAML
alias: sylius.product
filterable: false
only: ['index']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $this->assertRouteHasSyliusOption($result->get('sylius_product_index'), 'filterable', false);
    }

    public function testItGeneratesRoutingWithBcLayerDisabled(): void
    {
        $loader = $this->createLoaderWithMockedDependencies(false);

        $resource = <<<YAML
alias: sylius.product
only: ['delete']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $deleteRoute = $result->get('sylius_product_delete');
        $this->assertNotNull($deleteRoute);
        $this->assertContains('DELETE', $deleteRoute->getMethods());
        $this->assertContains('POST', $deleteRoute->getMethods());
        $this->assertEquals('/products/{id}/delete', $deleteRoute->getPath());
    }

    public function testItGeneratesRoutingWithBcLayerEnabled(): void
    {
        $this->markAsSkippedIfBcLayerCannotBeEnabled();

        $loader = $this->createLoaderWithMockedDependencies(true);

        $resource = <<<YAML
alias: sylius.product
only: ['delete']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $deleteRoute = $result->get('sylius_product_delete');
        $this->assertNotNull($deleteRoute);
        $this->assertEquals(['DELETE'], $deleteRoute->getMethods());
        $this->assertNotContains('POST', $deleteRoute->getMethods());
        $this->assertEquals('/products/{id}', $deleteRoute->getPath());
    }

    public function testItGeneratesBulkDeleteRoutingWithBcLayerDisabled(): void
    {
        $loader = $this->createLoaderWithMockedDependencies(false);

        $resource = <<<YAML
alias: sylius.product
only: ['bulkDelete']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $bulkDeleteRoute = $result->get('sylius_product_bulk_delete');
        $this->assertNotNull($bulkDeleteRoute);
        $this->assertContains('DELETE', $bulkDeleteRoute->getMethods());
        $this->assertContains('POST', $bulkDeleteRoute->getMethods());
        $this->assertEquals('/products/bulk-delete', $bulkDeleteRoute->getPath());
    }

    public function testItGeneratesBulkDeleteRoutingWithBcLayerEnabled(): void
    {
        $this->markAsSkippedIfBcLayerCannotBeEnabled();

        $loader = $this->createLoaderWithMockedDependencies(true);

        $resource = <<<YAML
alias: sylius.product
only: ['bulkDelete']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $bulkDeleteRoute = $result->get('sylius_product_bulk_delete');
        $this->assertNotNull($bulkDeleteRoute);
        $this->assertEquals(['DELETE'], $bulkDeleteRoute->getMethods());
        $this->assertNotContains('POST', $bulkDeleteRoute->getMethods());
        $this->assertEquals('/products/bulk-delete', $bulkDeleteRoute->getPath());
    }

    public function testItGeneratesUpdateRoutingWithBcLayerDisabled(): void
    {
        $loader = $this->createLoaderWithMockedDependencies(false);

        $resource = <<<YAML
alias: sylius.product
only: ['update']
YAML;

        $result = $loader->load($resource, 'sylius.resource');

        $updateRoute = $result->get('sylius_product_update');
        $this->assertNotNull($updateRoute);
        $this->assertContains('GET', $updateRoute->getMethods());
        $this->assertContains('PUT', $updateRoute->getMethods());
        $this->assertContains('PATCH', $updateRoute->getMethods());
        $this->assertContains('POST', $updateRoute->getMethods());
    }

    private function createLoader(?bool $bcLayerEnabled = null): ResourceLoader
    {
        $registry = $this->createMock(RegistryInterface::class);
        $routeFactory = $this->createMock(RouteFactoryInterface::class);

        return new ResourceLoader($registry, $routeFactory, null, $bcLayerEnabled);
    }

    private function createLoaderWithMockedDependencies(?bool $bcLayerEnabled = null): ResourceLoader
    {
        $registry = $this->createMock(RegistryInterface::class);
        $metadata = $this->createMock(MetadataInterface::class);
        $routeFactory = $this->createMock(RouteFactoryInterface::class);
        $routeCollection = new RouteCollection();

        $metadata->method('getApplicationName')->willReturn('sylius');
        $metadata->method('getName')->willReturn('product');
        $metadata->method('getPluralName')->willReturn('products');
        $metadata->method('getServiceId')->willReturn('sylius.controller.product');

        $registry->method('get')->with('sylius.product')->willReturn($metadata);
        $routeFactory->method('createRouteCollection')->willReturn($routeCollection);
        $routeFactory->method('createRoute')->willReturnCallback(
            fn (string $path, array $defaults, array $requirements = [], array $options = [], string $host = '', array $schemes = [], array $methods = []) => new Route($path, $defaults, $requirements, $options, $host, $schemes, $methods),
        );

        return new ResourceLoader($registry, $routeFactory, null, $bcLayerEnabled);
    }

    private function assertRouteHasSyliusOption(?Route $route, string $key, mixed $expectedValue): void
    {
        $this->assertNotNull($route);
        $this->assertArrayHasKey('_sylius', $route->getDefaults());
        $this->assertArrayHasKey($key, $route->getDefaults()['_sylius']);
        $this->assertEquals($expectedValue, $route->getDefaults()['_sylius'][$key]);
    }

    private function markAsSkippedIfBcLayerCannotBeEnabled(): void
    {
        if (!class_exists(Transliterator::class)) {
            $this->markTestSkipped('This test requires The Behat Transliterator.');
        }
    }
}
