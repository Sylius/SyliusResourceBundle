<?php

namespace Symfony\Config\Doctrine;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Orm'.\DIRECTORY_SEPARATOR.'ControllerResolverConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Orm'.\DIRECTORY_SEPARATOR.'EntityManagerConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class OrmConfig 
{
    private $defaultEntityManager;
    private $autoGenerateProxyClasses;
    private $enableLazyGhostObjects;
    private $proxyDir;
    private $proxyNamespace;
    private $controllerResolver;
    private $entityManagers;
    private $resolveTargetEntities;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultEntityManager($value): static
    {
        $this->_usedProperties['defaultEntityManager'] = true;
        $this->defaultEntityManager = $value;

        return $this;
    }

    /**
     * Auto generate mode possible values are: "NEVER", "ALWAYS", "FILE_NOT_EXISTS", "EVAL", "FILE_NOT_EXISTS_OR_CHANGED"
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function autoGenerateProxyClasses($value): static
    {
        $this->_usedProperties['autoGenerateProxyClasses'] = true;
        $this->autoGenerateProxyClasses = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enableLazyGhostObjects($value): static
    {
        $this->_usedProperties['enableLazyGhostObjects'] = true;
        $this->enableLazyGhostObjects = $value;

        return $this;
    }

    /**
     * @default '%kernel.cache_dir%/doctrine/orm/Proxies'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function proxyDir($value): static
    {
        $this->_usedProperties['proxyDir'] = true;
        $this->proxyDir = $value;

        return $this;
    }

    /**
     * @default 'Proxies'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function proxyNamespace($value): static
    {
        $this->_usedProperties['proxyNamespace'] = true;
        $this->proxyNamespace = $value;

        return $this;
    }

    /**
     * @default {"enabled":true,"auto_mapping":true,"evict_cache":false}
    */
    public function controllerResolver(array $value = []): \Symfony\Config\Doctrine\Orm\ControllerResolverConfig
    {
        if (null === $this->controllerResolver) {
            $this->_usedProperties['controllerResolver'] = true;
            $this->controllerResolver = new \Symfony\Config\Doctrine\Orm\ControllerResolverConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "controllerResolver()" has already been initialized. You cannot pass values the second time you call controllerResolver().');
        }

        return $this->controllerResolver;
    }

    public function entityManager(string $name, array $value = []): \Symfony\Config\Doctrine\Orm\EntityManagerConfig
    {
        if (!isset($this->entityManagers[$name])) {
            $this->_usedProperties['entityManagers'] = true;
            $this->entityManagers[$name] = new \Symfony\Config\Doctrine\Orm\EntityManagerConfig($value);
        } elseif (1 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "entityManager()" has already been initialized. You cannot pass values the second time you call entityManager().');
        }

        return $this->entityManagers[$name];
    }

    /**
     * @return $this
     */
    public function resolveTargetEntity(string $interface, mixed $value): static
    {
        $this->_usedProperties['resolveTargetEntities'] = true;
        $this->resolveTargetEntities[$interface] = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('default_entity_manager', $value)) {
            $this->_usedProperties['defaultEntityManager'] = true;
            $this->defaultEntityManager = $value['default_entity_manager'];
            unset($value['default_entity_manager']);
        }

        if (array_key_exists('auto_generate_proxy_classes', $value)) {
            $this->_usedProperties['autoGenerateProxyClasses'] = true;
            $this->autoGenerateProxyClasses = $value['auto_generate_proxy_classes'];
            unset($value['auto_generate_proxy_classes']);
        }

        if (array_key_exists('enable_lazy_ghost_objects', $value)) {
            $this->_usedProperties['enableLazyGhostObjects'] = true;
            $this->enableLazyGhostObjects = $value['enable_lazy_ghost_objects'];
            unset($value['enable_lazy_ghost_objects']);
        }

        if (array_key_exists('proxy_dir', $value)) {
            $this->_usedProperties['proxyDir'] = true;
            $this->proxyDir = $value['proxy_dir'];
            unset($value['proxy_dir']);
        }

        if (array_key_exists('proxy_namespace', $value)) {
            $this->_usedProperties['proxyNamespace'] = true;
            $this->proxyNamespace = $value['proxy_namespace'];
            unset($value['proxy_namespace']);
        }

        if (array_key_exists('controller_resolver', $value)) {
            $this->_usedProperties['controllerResolver'] = true;
            $this->controllerResolver = new \Symfony\Config\Doctrine\Orm\ControllerResolverConfig($value['controller_resolver']);
            unset($value['controller_resolver']);
        }

        if (array_key_exists('entity_managers', $value)) {
            $this->_usedProperties['entityManagers'] = true;
            $this->entityManagers = array_map(function ($v) { return new \Symfony\Config\Doctrine\Orm\EntityManagerConfig($v); }, $value['entity_managers']);
            unset($value['entity_managers']);
        }

        if (array_key_exists('resolve_target_entities', $value)) {
            $this->_usedProperties['resolveTargetEntities'] = true;
            $this->resolveTargetEntities = $value['resolve_target_entities'];
            unset($value['resolve_target_entities']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['defaultEntityManager'])) {
            $output['default_entity_manager'] = $this->defaultEntityManager;
        }
        if (isset($this->_usedProperties['autoGenerateProxyClasses'])) {
            $output['auto_generate_proxy_classes'] = $this->autoGenerateProxyClasses;
        }
        if (isset($this->_usedProperties['enableLazyGhostObjects'])) {
            $output['enable_lazy_ghost_objects'] = $this->enableLazyGhostObjects;
        }
        if (isset($this->_usedProperties['proxyDir'])) {
            $output['proxy_dir'] = $this->proxyDir;
        }
        if (isset($this->_usedProperties['proxyNamespace'])) {
            $output['proxy_namespace'] = $this->proxyNamespace;
        }
        if (isset($this->_usedProperties['controllerResolver'])) {
            $output['controller_resolver'] = $this->controllerResolver->toArray();
        }
        if (isset($this->_usedProperties['entityManagers'])) {
            $output['entity_managers'] = array_map(function ($v) { return $v->toArray(); }, $this->entityManagers);
        }
        if (isset($this->_usedProperties['resolveTargetEntities'])) {
            $output['resolve_target_entities'] = $this->resolveTargetEntities;
        }

        return $output;
    }

}
