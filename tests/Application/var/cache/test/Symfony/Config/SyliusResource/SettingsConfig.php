<?php

namespace Symfony\Config\SyliusResource;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class SettingsConfig 
{
    private $paginate;
    private $limit;
    private $allowedPaginate;
    private $defaultPageSize;
    private $defaultTemplatesDir;
    private $sortable;
    private $sorting;
    private $filterable;
    private $criteria;
    private $stateMachineComponent;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function paginate(mixed $value = NULL): static
    {
        $this->_usedProperties['paginate'] = true;
        $this->paginate = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function limit(mixed $value = NULL): static
    {
        $this->_usedProperties['limit'] = true;
        $this->limit = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|int> $value
     *
     * @return $this
     */
    public function allowedPaginate(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['allowedPaginate'] = true;
        $this->allowedPaginate = $value;

        return $this;
    }

    /**
     * @default 10
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function defaultPageSize($value): static
    {
        $this->_usedProperties['defaultPageSize'] = true;
        $this->defaultPageSize = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultTemplatesDir($value): static
    {
        $this->_usedProperties['defaultTemplatesDir'] = true;
        $this->defaultTemplatesDir = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function sortable($value): static
    {
        $this->_usedProperties['sortable'] = true;
        $this->sortable = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function sorting(mixed $value = NULL): static
    {
        $this->_usedProperties['sorting'] = true;
        $this->sorting = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function filterable($value): static
    {
        $this->_usedProperties['filterable'] = true;
        $this->filterable = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function criteria(mixed $value = NULL): static
    {
        $this->_usedProperties['criteria'] = true;
        $this->criteria = $value;

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

    public function __construct(array $value = [])
    {
        if (array_key_exists('paginate', $value)) {
            $this->_usedProperties['paginate'] = true;
            $this->paginate = $value['paginate'];
            unset($value['paginate']);
        }

        if (array_key_exists('limit', $value)) {
            $this->_usedProperties['limit'] = true;
            $this->limit = $value['limit'];
            unset($value['limit']);
        }

        if (array_key_exists('allowed_paginate', $value)) {
            $this->_usedProperties['allowedPaginate'] = true;
            $this->allowedPaginate = $value['allowed_paginate'];
            unset($value['allowed_paginate']);
        }

        if (array_key_exists('default_page_size', $value)) {
            $this->_usedProperties['defaultPageSize'] = true;
            $this->defaultPageSize = $value['default_page_size'];
            unset($value['default_page_size']);
        }

        if (array_key_exists('default_templates_dir', $value)) {
            $this->_usedProperties['defaultTemplatesDir'] = true;
            $this->defaultTemplatesDir = $value['default_templates_dir'];
            unset($value['default_templates_dir']);
        }

        if (array_key_exists('sortable', $value)) {
            $this->_usedProperties['sortable'] = true;
            $this->sortable = $value['sortable'];
            unset($value['sortable']);
        }

        if (array_key_exists('sorting', $value)) {
            $this->_usedProperties['sorting'] = true;
            $this->sorting = $value['sorting'];
            unset($value['sorting']);
        }

        if (array_key_exists('filterable', $value)) {
            $this->_usedProperties['filterable'] = true;
            $this->filterable = $value['filterable'];
            unset($value['filterable']);
        }

        if (array_key_exists('criteria', $value)) {
            $this->_usedProperties['criteria'] = true;
            $this->criteria = $value['criteria'];
            unset($value['criteria']);
        }

        if (array_key_exists('state_machine_component', $value)) {
            $this->_usedProperties['stateMachineComponent'] = true;
            $this->stateMachineComponent = $value['state_machine_component'];
            unset($value['state_machine_component']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['paginate'])) {
            $output['paginate'] = $this->paginate;
        }
        if (isset($this->_usedProperties['limit'])) {
            $output['limit'] = $this->limit;
        }
        if (isset($this->_usedProperties['allowedPaginate'])) {
            $output['allowed_paginate'] = $this->allowedPaginate;
        }
        if (isset($this->_usedProperties['defaultPageSize'])) {
            $output['default_page_size'] = $this->defaultPageSize;
        }
        if (isset($this->_usedProperties['defaultTemplatesDir'])) {
            $output['default_templates_dir'] = $this->defaultTemplatesDir;
        }
        if (isset($this->_usedProperties['sortable'])) {
            $output['sortable'] = $this->sortable;
        }
        if (isset($this->_usedProperties['sorting'])) {
            $output['sorting'] = $this->sorting;
        }
        if (isset($this->_usedProperties['filterable'])) {
            $output['filterable'] = $this->filterable;
        }
        if (isset($this->_usedProperties['criteria'])) {
            $output['criteria'] = $this->criteria;
        }
        if (isset($this->_usedProperties['stateMachineComponent'])) {
            $output['state_machine_component'] = $this->stateMachineComponent;
        }

        return $output;
    }

}
