<?php

namespace Symfony\Config\SyliusGrid;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class TemplatesConfig 
{
    private $filter;
    private $action;
    private $bulkAction;
    private $_usedProperties = [];

    /**
     * @return $this
     */
    public function filter(string $name, mixed $value): static
    {
        $this->_usedProperties['filter'] = true;
        $this->filter[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function action(string $name, mixed $value): static
    {
        $this->_usedProperties['action'] = true;
        $this->action[$name] = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function bulkAction(string $name, mixed $value): static
    {
        $this->_usedProperties['bulkAction'] = true;
        $this->bulkAction[$name] = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('filter', $value)) {
            $this->_usedProperties['filter'] = true;
            $this->filter = $value['filter'];
            unset($value['filter']);
        }

        if (array_key_exists('action', $value)) {
            $this->_usedProperties['action'] = true;
            $this->action = $value['action'];
            unset($value['action']);
        }

        if (array_key_exists('bulk_action', $value)) {
            $this->_usedProperties['bulkAction'] = true;
            $this->bulkAction = $value['bulk_action'];
            unset($value['bulk_action']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['filter'])) {
            $output['filter'] = $this->filter;
        }
        if (isset($this->_usedProperties['action'])) {
            $output['action'] = $this->action;
        }
        if (isset($this->_usedProperties['bulkAction'])) {
            $output['bulk_action'] = $this->bulkAction;
        }

        return $output;
    }

}
