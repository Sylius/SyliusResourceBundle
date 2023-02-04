<?php

namespace Symfony\Config\SyliusGrid;

require_once __DIR__.\DIRECTORY_SEPARATOR.'GridsConfig'.\DIRECTORY_SEPARATOR.'DriverConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'GridsConfig'.\DIRECTORY_SEPARATOR.'FieldsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'GridsConfig'.\DIRECTORY_SEPARATOR.'FiltersConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'GridsConfig'.\DIRECTORY_SEPARATOR.'ActionsConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'GridsConfig'.\DIRECTORY_SEPARATOR.'RemovalsConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class GridsConfig 
{
    private $extends;
    private $driver;
    private $sorting;
    private $limits;
    private $fields;
    private $filters;
    private $actions;
    private $removals;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function extends($value): static
    {
        $this->_usedProperties['extends'] = true;
        $this->extends = $value;

        return $this;
    }

    /**
     * @default {"name":"doctrine\/orm","options":[]}
    */
    public function driver(array $value = []): \Symfony\Config\SyliusGrid\GridsConfig\DriverConfig
    {
        if (null === $this->driver) {
            $this->_usedProperties['driver'] = true;
            $this->driver = new \Symfony\Config\SyliusGrid\GridsConfig\DriverConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "driver()" has already been initialized. You cannot pass values the second time you call driver().');
        }

        return $this->driver;
    }

    /**
     * @return $this
     */
    public function sorting(string $name, mixed $value): static
    {
        $this->_usedProperties['sorting'] = true;
        $this->sorting[$name] = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|int> $value
     *
     * @return $this
     */
    public function limits(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['limits'] = true;
        $this->limits = $value;

        return $this;
    }

    public function fields(string $name, array $value = []): \Symfony\Config\SyliusGrid\GridsConfig\FieldsConfig
    {
        if (!isset($this->fields[$name])) {
            $this->_usedProperties['fields'] = true;
            $this->fields[$name] = new \Symfony\Config\SyliusGrid\GridsConfig\FieldsConfig($value);
        } elseif (1 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "fields()" has already been initialized. You cannot pass values the second time you call fields().');
        }

        return $this->fields[$name];
    }

    public function filters(string $name, array $value = []): \Symfony\Config\SyliusGrid\GridsConfig\FiltersConfig
    {
        if (!isset($this->filters[$name])) {
            $this->_usedProperties['filters'] = true;
            $this->filters[$name] = new \Symfony\Config\SyliusGrid\GridsConfig\FiltersConfig($value);
        } elseif (1 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "filters()" has already been initialized. You cannot pass values the second time you call filters().');
        }

        return $this->filters[$name];
    }

    public function actions(string $name, array $value = []): \Symfony\Config\SyliusGrid\GridsConfig\ActionsConfig
    {
        if (!isset($this->actions[$name])) {
            $this->_usedProperties['actions'] = true;
            $this->actions[$name] = new \Symfony\Config\SyliusGrid\GridsConfig\ActionsConfig($value);
        } elseif (1 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "actions()" has already been initialized. You cannot pass values the second time you call actions().');
        }

        return $this->actions[$name];
    }

    public function removals(array $value = []): \Symfony\Config\SyliusGrid\GridsConfig\RemovalsConfig
    {
        if (null === $this->removals) {
            $this->_usedProperties['removals'] = true;
            $this->removals = new \Symfony\Config\SyliusGrid\GridsConfig\RemovalsConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "removals()" has already been initialized. You cannot pass values the second time you call removals().');
        }

        return $this->removals;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('extends', $value)) {
            $this->_usedProperties['extends'] = true;
            $this->extends = $value['extends'];
            unset($value['extends']);
        }

        if (array_key_exists('driver', $value)) {
            $this->_usedProperties['driver'] = true;
            $this->driver = new \Symfony\Config\SyliusGrid\GridsConfig\DriverConfig($value['driver']);
            unset($value['driver']);
        }

        if (array_key_exists('sorting', $value)) {
            $this->_usedProperties['sorting'] = true;
            $this->sorting = $value['sorting'];
            unset($value['sorting']);
        }

        if (array_key_exists('limits', $value)) {
            $this->_usedProperties['limits'] = true;
            $this->limits = $value['limits'];
            unset($value['limits']);
        }

        if (array_key_exists('fields', $value)) {
            $this->_usedProperties['fields'] = true;
            $this->fields = array_map(function ($v) { return new \Symfony\Config\SyliusGrid\GridsConfig\FieldsConfig($v); }, $value['fields']);
            unset($value['fields']);
        }

        if (array_key_exists('filters', $value)) {
            $this->_usedProperties['filters'] = true;
            $this->filters = array_map(function ($v) { return new \Symfony\Config\SyliusGrid\GridsConfig\FiltersConfig($v); }, $value['filters']);
            unset($value['filters']);
        }

        if (array_key_exists('actions', $value)) {
            $this->_usedProperties['actions'] = true;
            $this->actions = array_map(function ($v) { return new \Symfony\Config\SyliusGrid\GridsConfig\ActionsConfig($v); }, $value['actions']);
            unset($value['actions']);
        }

        if (array_key_exists('removals', $value)) {
            $this->_usedProperties['removals'] = true;
            $this->removals = new \Symfony\Config\SyliusGrid\GridsConfig\RemovalsConfig($value['removals']);
            unset($value['removals']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['extends'])) {
            $output['extends'] = $this->extends;
        }
        if (isset($this->_usedProperties['driver'])) {
            $output['driver'] = $this->driver->toArray();
        }
        if (isset($this->_usedProperties['sorting'])) {
            $output['sorting'] = $this->sorting;
        }
        if (isset($this->_usedProperties['limits'])) {
            $output['limits'] = $this->limits;
        }
        if (isset($this->_usedProperties['fields'])) {
            $output['fields'] = array_map(function ($v) { return $v->toArray(); }, $this->fields);
        }
        if (isset($this->_usedProperties['filters'])) {
            $output['filters'] = array_map(function ($v) { return $v->toArray(); }, $this->filters);
        }
        if (isset($this->_usedProperties['actions'])) {
            $output['actions'] = array_map(function ($v) { return $v->toArray(); }, $this->actions);
        }
        if (isset($this->_usedProperties['removals'])) {
            $output['removals'] = $this->removals->toArray();
        }

        return $output;
    }

}
