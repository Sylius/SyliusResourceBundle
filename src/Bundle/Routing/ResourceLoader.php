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

namespace Sylius\Bundle\ResourceBundle\Routing;

use Behat\Transliterator\Transliterator;
use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Resource\Exception\RuntimeException;
use Sylius\Resource\Metadata\Inflector\Inflector;
use Sylius\Resource\Metadata\Inflector\InflectorInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

/**
 * @deprecated use Sylius\Resource\Symfony\Routing\Loader\ResourceLoader instead
 */
final class ResourceLoader extends Loader
{
    private InflectorInterface $inflector;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RouteFactoryInterface $routeFactory,
        ?string $env = null,
        private ?bool $routingPathBcLayer = null,
        ?InflectorInterface $inflector = null,
    ) {
        parent::__construct($env);

        $this->inflector = $inflector ?? new Inflector();
    }

    public function load($resource, $type = null): RouteCollection
    {
        $processor = new Processor();
        $configurationDefinition = new Configuration();

        $configuration = Yaml::parse($resource);
        $configuration = $processor->processConfiguration($configurationDefinition, ['routing' => $configuration]);

        if (!empty($configuration['only']) && !empty($configuration['except'])) {
            throw new \InvalidArgumentException('You can configure only one of "except" & "only" options.');
        }

        $routesToGenerate = ['show', 'index', 'create', 'update', 'delete', 'bulkDelete'];

        if (!empty($configuration['only'])) {
            $routesToGenerate = $configuration['only'];
        }
        if (!empty($configuration['except'])) {
            $routesToGenerate = array_diff($routesToGenerate, $configuration['except']);
        }

        $isApi = $type === 'sylius.resource_api';

        /** @var MetadataInterface $metadata */
        $metadata = $this->resourceRegistry->get($configuration['alias']);
        $routes = $this->routeFactory->createRouteCollection();

        $rootPath = $configuration['path'] ?? $this->getRootPath($metadata->getPluralName());
        $identifier = sprintf('{%s}', $configuration['identifier']);

        /** @var bool $bcLayerEnabled */
        $bcLayerEnabled = $this->routingPathBcLayer ?? true;
        $trailingSlash = $bcLayerEnabled ? '/' : '';

        if (in_array('index', $routesToGenerate, true)) {
            $indexRoute = $this->createRoute($metadata, $configuration, $rootPath . $trailingSlash, 'index', ['GET'], $isApi);
            $routes->add($this->getRouteName($metadata, $configuration, 'index'), $indexRoute);
        }

        if (in_array('create', $routesToGenerate, true)) {
            $createRoute = $this->createRoute($metadata, $configuration, $isApi ? $rootPath . $trailingSlash : $rootPath . '/new', 'create', $isApi ? ['POST'] : ['GET', 'POST'], $isApi);
            $routes->add($this->getRouteName($metadata, $configuration, 'create'), $createRoute);
        }

        if (in_array('update', $routesToGenerate, true)) {
            $httpMethods = ['GET', 'PUT', 'PATCH'];
            if (!$bcLayerEnabled) {
                $httpMethods[] = 'POST';
            }

            $updateRoute = $this->createRoute($metadata, $configuration, $isApi ? $rootPath . '/' . $identifier : $rootPath . '/' . $identifier . '/edit', 'update', $isApi ? ['PUT', 'PATCH'] : $httpMethods, $isApi);
            $routes->add($this->getRouteName($metadata, $configuration, 'update'), $updateRoute);
        }

        if (in_array('show', $routesToGenerate, true)) {
            $showRoute = $this->createRoute($metadata, $configuration, $rootPath . '/' . $identifier, 'show', ['GET'], $isApi);
            $routes->add($this->getRouteName($metadata, $configuration, 'show'), $showRoute);
        }

        if (!$isApi && in_array('bulkDelete', $routesToGenerate, true)) {
            $httpMethods = ['DELETE'];
            if (!$bcLayerEnabled) {
                $httpMethods[] = 'POST';
            }

            $bulkDeleteRoute = $this->createRoute($metadata, $configuration, $rootPath . '/' . 'bulk-delete', 'bulkDelete', $httpMethods, $isApi);
            $routes->add($this->getRouteName($metadata, $configuration, 'bulk_delete'), $bulkDeleteRoute);
        }

        if (in_array('delete', $routesToGenerate, true)) {
            $httpMethods = ['DELETE'];
            if (!$bcLayerEnabled) {
                $httpMethods[] = 'POST';
            }

            $deleteRoute = $this->createRoute($metadata, $configuration, $isApi ? $rootPath . '/' . $identifier : $rootPath . '/' . $identifier . ($bcLayerEnabled ? '' : '/delete'), 'delete', $isApi ? ['DELETE'] : $httpMethods, $isApi);
            $routes->add($this->getRouteName($metadata, $configuration, 'delete'), $deleteRoute);
        }

        return $routes;
    }

    public function supports($resource, $type = null): bool
    {
        return 'sylius.resource' === $type || 'sylius.resource_api' === $type;
    }

    private function getRootPath(string $pluralName): string
    {
        if ($this->routingPathBcLayer) {
            if (!class_exists(Urlizer::class) || !class_exists(Transliterator::class)) {
                throw new RuntimeException('Cannot use the routing bc-layer when the "behat/transliterator" package is not installed. Try to disable the routing path bc-layer in the Sylius Resource Bundle configuration using "sylius_resource.routing_path_bc_layer: false"');
            }

            return sprintf('/%s', Urlizer::urlize($pluralName));
        }

        return $this->inflector->dashize($pluralName);
    }

    private function createRoute(
        MetadataInterface $metadata,
        array $configuration,
        string $path,
        string $actionName,
        array $methods,
        bool $isApi = false,
    ): Route {
        $defaults = [
            '_controller' => $metadata->getServiceId('controller') . sprintf('::%sAction', $actionName),
        ];

        if ($isApi && 'index' === $actionName) {
            $defaults['_sylius']['serialization_groups'] = ['Default'];
        }
        if ($isApi && in_array($actionName, ['show', 'create', 'update'], true)) {
            $defaults['_sylius']['serialization_groups'] = ['Default', 'Detailed'];
        }
        if ($isApi && 'delete' === $actionName) {
            $defaults['_sylius']['csrf_protection'] = false;
        }
        if (isset($configuration['grid']) && 'index' === $actionName) {
            $defaults['_sylius']['grid'] = $configuration['grid'];
        }
        if (isset($configuration['form']) && in_array($actionName, ['create', 'update'], true)) {
            $defaults['_sylius']['form'] = $configuration['form'];
        }
        if (isset($configuration['serialization_version'])) {
            $defaults['_sylius']['serialization_version'] = $configuration['serialization_version'];
        }
        if (isset($configuration['section'])) {
            $defaults['_sylius']['section'] = $configuration['section'];
        }
        if (!empty($configuration['criteria'])) {
            $defaults['_sylius']['criteria'] = $configuration['criteria'];
        }
        if (array_key_exists('filterable', $configuration)) {
            $defaults['_sylius']['filterable'] = $configuration['filterable'];
        }
        if (isset($configuration['templates']) && in_array($actionName, ['show', 'index', 'create', 'update'], true)) {
            $defaults['_sylius']['template'] = sprintf(
                false === strpos($configuration['templates'], ':') ? '%s/%s.html.twig' : '%s:%s.html.twig',
                $configuration['templates'],
                $actionName,
            );
        }
        if (isset($configuration['redirect']) && in_array($actionName, ['create', 'update'], true)) {
            $defaults['_sylius']['redirect'] = $this->getRouteName($metadata, $configuration, $configuration['redirect']);
        }
        if (isset($configuration['permission'])) {
            $defaults['_sylius']['permission'] = $configuration['permission'];
        }
        if (isset($configuration['vars']['all'])) {
            $defaults['_sylius']['vars'] = $configuration['vars']['all'];
        }

        if (isset($configuration['vars'][$actionName])) {
            $vars = $configuration['vars']['all'] ?? [];
            $defaults['_sylius']['vars'] = array_merge($vars, $configuration['vars'][$actionName]);
        }

        if ($actionName === 'bulkDelete') {
            $defaults['_sylius']['paginate'] = false;
            $defaults['_sylius']['repository'] = [
                'method' => 'findById',
                'arguments' => ['$ids'],
            ];
        }

        $condition = $configuration['condition'] ?? '';

        return $this->routeFactory->createRoute($path, $defaults, [], [], '', [], $methods, $condition);
    }

    private function getRouteName(MetadataInterface $metadata, array $configuration, string $actionName): string
    {
        $sectionPrefix = isset($configuration['section']) ? $configuration['section'] . '_' : '';

        return sprintf('%s_%s%s_%s', $metadata->getApplicationName(), $sectionPrefix, $metadata->getName(), $actionName);
    }
}
