<?php

namespace Symfony\Config\JmsSerializer\Subscribers;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class DoctrineProxyConfig 
{
    private $initializeExcluded;
    private $initializeVirtualTypes;
    private $_usedProperties = [];

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function initializeExcluded($value): static
    {
        $this->_usedProperties['initializeExcluded'] = true;
        $this->initializeExcluded = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function initializeVirtualTypes($value): static
    {
        $this->_usedProperties['initializeVirtualTypes'] = true;
        $this->initializeVirtualTypes = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('initialize_excluded', $value)) {
            $this->_usedProperties['initializeExcluded'] = true;
            $this->initializeExcluded = $value['initialize_excluded'];
            unset($value['initialize_excluded']);
        }

        if (array_key_exists('initialize_virtual_types', $value)) {
            $this->_usedProperties['initializeVirtualTypes'] = true;
            $this->initializeVirtualTypes = $value['initialize_virtual_types'];
            unset($value['initialize_virtual_types']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['initializeExcluded'])) {
            $output['initialize_excluded'] = $this->initializeExcluded;
        }
        if (isset($this->_usedProperties['initializeVirtualTypes'])) {
            $output['initialize_virtual_types'] = $this->initializeVirtualTypes;
        }

        return $output;
    }

}
