<?php

namespace Symfony\Config\SyliusResource;

require_once __DIR__.\DIRECTORY_SEPARATOR.'ResourcesConfig'.\DIRECTORY_SEPARATOR.'ClassesConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'ResourcesConfig'.\DIRECTORY_SEPARATOR.'TranslationConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ResourcesConfig 
{
    private $driver;
    private $options;
    private $templates;
    private $stateMachineComponent;
    private $classes;
    private $translation;
    private $_usedProperties = [];

    /**
     * @default 'doctrine/orm'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function driver($value): static
    {
        $this->_usedProperties['driver'] = true;
        $this->driver = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function options(mixed $value): static
    {
        $this->_usedProperties['options'] = true;
        $this->options = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function templates($value): static
    {
        $this->_usedProperties['templates'] = true;
        $this->templates = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function stateMachineComponent($value): static
    {
        $this->_usedProperties['stateMachineComponent'] = true;
        $this->stateMachineComponent = $value;

        return $this;
    }

    /**
     * @default {"controller":"Sylius\\Bundle\\ResourceBundle\\Controller\\ResourceController","factory":"Sylius\\Component\\Resource\\Factory\\Factory","form":"Sylius\\Bundle\\ResourceBundle\\Form\\Type\\DefaultResourceType"}
    */
    public function classes(array $value = []): \Symfony\Config\SyliusResource\ResourcesConfig\ClassesConfig
    {
        if (null === $this->classes) {
            $this->_usedProperties['classes'] = true;
            $this->classes = new \Symfony\Config\SyliusResource\ResourcesConfig\ClassesConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "classes()" has already been initialized. You cannot pass values the second time you call classes().');
        }

        return $this->classes;
    }

    public function translation(array $value = []): \Symfony\Config\SyliusResource\ResourcesConfig\TranslationConfig
    {
        if (null === $this->translation) {
            $this->_usedProperties['translation'] = true;
            $this->translation = new \Symfony\Config\SyliusResource\ResourcesConfig\TranslationConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "translation()" has already been initialized. You cannot pass values the second time you call translation().');
        }

        return $this->translation;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('driver', $value)) {
            $this->_usedProperties['driver'] = true;
            $this->driver = $value['driver'];
            unset($value['driver']);
        }

        if (array_key_exists('options', $value)) {
            $this->_usedProperties['options'] = true;
            $this->options = $value['options'];
            unset($value['options']);
        }

        if (array_key_exists('templates', $value)) {
            $this->_usedProperties['templates'] = true;
            $this->templates = $value['templates'];
            unset($value['templates']);
        }

        if (array_key_exists('state_machine_component', $value)) {
            $this->_usedProperties['stateMachineComponent'] = true;
            $this->stateMachineComponent = $value['state_machine_component'];
            unset($value['state_machine_component']);
        }

        if (array_key_exists('classes', $value)) {
            $this->_usedProperties['classes'] = true;
            $this->classes = new \Symfony\Config\SyliusResource\ResourcesConfig\ClassesConfig($value['classes']);
            unset($value['classes']);
        }

        if (array_key_exists('translation', $value)) {
            $this->_usedProperties['translation'] = true;
            $this->translation = new \Symfony\Config\SyliusResource\ResourcesConfig\TranslationConfig($value['translation']);
            unset($value['translation']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['driver'])) {
            $output['driver'] = $this->driver;
        }
        if (isset($this->_usedProperties['options'])) {
            $output['options'] = $this->options;
        }
        if (isset($this->_usedProperties['templates'])) {
            $output['templates'] = $this->templates;
        }
        if (isset($this->_usedProperties['stateMachineComponent'])) {
            $output['state_machine_component'] = $this->stateMachineComponent;
        }
        if (isset($this->_usedProperties['classes'])) {
            $output['classes'] = $this->classes->toArray();
        }
        if (isset($this->_usedProperties['translation'])) {
            $output['translation'] = $this->translation->toArray();
        }

        return $output;
    }

}
