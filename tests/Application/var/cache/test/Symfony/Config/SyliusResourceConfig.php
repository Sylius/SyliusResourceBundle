<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'SyliusResource'.\DIRECTORY_SEPARATOR.'ResourcesConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SyliusResource'.\DIRECTORY_SEPARATOR.'SettingsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SyliusResource'.\DIRECTORY_SEPARATOR.'TranslationConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SyliusResource'.\DIRECTORY_SEPARATOR.'MappingConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Loader\ParamConfigurator;

/**
 * This class is automatically generated to help in creating a config.
 */
class SyliusResourceConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $resources;
    private $settings;
    private $translation;
    private $drivers;
    private $mapping;
    private $authorizationChecker;
    private $_usedProperties = [];

    public function resources(string $name, array $value = []): \Symfony\Config\SyliusResource\ResourcesConfig
    {
        if (!isset($this->resources[$name])) {
            $this->_usedProperties['resources'] = true;
            $this->resources[$name] = new \Symfony\Config\SyliusResource\ResourcesConfig($value);
        } elseif (1 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "resources()" has already been initialized. You cannot pass values the second time you call resources().');
        }

        return $this->resources[$name];
    }

    /**
     * @default {"paginate":null,"limit":null,"allowed_paginate":[10,20,30],"default_page_size":10,"default_templates_dir":null,"sortable":false,"sorting":null,"filterable":false,"criteria":null,"state_machine_component":null}
    */
    public function settings(array $value = []): \Symfony\Config\SyliusResource\SettingsConfig
    {
        if (null === $this->settings) {
            $this->_usedProperties['settings'] = true;
            $this->settings = new \Symfony\Config\SyliusResource\SettingsConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "settings()" has already been initialized. You cannot pass values the second time you call settings().');
        }

        return $this->settings;
    }

    /**
     * @default {"enabled":true,"locale_provider":"sylius.translation_locale_provider.immutable"}
    */
    public function translation(array $value = []): \Symfony\Config\SyliusResource\TranslationConfig
    {
        if (null === $this->translation) {
            $this->_usedProperties['translation'] = true;
            $this->translation = new \Symfony\Config\SyliusResource\TranslationConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "translation()" has already been initialized. You cannot pass values the second time you call translation().');
        }

        return $this->translation;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function drivers(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['drivers'] = true;
        $this->drivers = $value;

        return $this;
    }

    /**
     * @default {"paths":[]}
    */
    public function mapping(array $value = []): \Symfony\Config\SyliusResource\MappingConfig
    {
        if (null === $this->mapping) {
            $this->_usedProperties['mapping'] = true;
            $this->mapping = new \Symfony\Config\SyliusResource\MappingConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "mapping()" has already been initialized. You cannot pass values the second time you call mapping().');
        }

        return $this->mapping;
    }

    /**
     * @default 'sylius.resource_controller.authorization_checker.disabled'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function authorizationChecker($value): static
    {
        $this->_usedProperties['authorizationChecker'] = true;
        $this->authorizationChecker = $value;

        return $this;
    }

    public function getExtensionAlias(): string
    {
        return 'sylius_resource';
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('resources', $value)) {
            $this->_usedProperties['resources'] = true;
            $this->resources = array_map(function ($v) { return new \Symfony\Config\SyliusResource\ResourcesConfig($v); }, $value['resources']);
            unset($value['resources']);
        }

        if (array_key_exists('settings', $value)) {
            $this->_usedProperties['settings'] = true;
            $this->settings = new \Symfony\Config\SyliusResource\SettingsConfig($value['settings']);
            unset($value['settings']);
        }

        if (array_key_exists('translation', $value)) {
            $this->_usedProperties['translation'] = true;
            $this->translation = new \Symfony\Config\SyliusResource\TranslationConfig($value['translation']);
            unset($value['translation']);
        }

        if (array_key_exists('drivers', $value)) {
            $this->_usedProperties['drivers'] = true;
            $this->drivers = $value['drivers'];
            unset($value['drivers']);
        }

        if (array_key_exists('mapping', $value)) {
            $this->_usedProperties['mapping'] = true;
            $this->mapping = new \Symfony\Config\SyliusResource\MappingConfig($value['mapping']);
            unset($value['mapping']);
        }

        if (array_key_exists('authorization_checker', $value)) {
            $this->_usedProperties['authorizationChecker'] = true;
            $this->authorizationChecker = $value['authorization_checker'];
            unset($value['authorization_checker']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['resources'])) {
            $output['resources'] = array_map(function ($v) { return $v->toArray(); }, $this->resources);
        }
        if (isset($this->_usedProperties['settings'])) {
            $output['settings'] = $this->settings->toArray();
        }
        if (isset($this->_usedProperties['translation'])) {
            $output['translation'] = $this->translation->toArray();
        }
        if (isset($this->_usedProperties['drivers'])) {
            $output['drivers'] = $this->drivers;
        }
        if (isset($this->_usedProperties['mapping'])) {
            $output['mapping'] = $this->mapping->toArray();
        }
        if (isset($this->_usedProperties['authorizationChecker'])) {
            $output['authorization_checker'] = $this->authorizationChecker;
        }

        return $output;
    }

}
