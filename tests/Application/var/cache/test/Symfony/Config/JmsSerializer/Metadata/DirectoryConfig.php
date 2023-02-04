<?php

namespace Symfony\Config\JmsSerializer\Metadata;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class DirectoryConfig 
{
    private $path;
    private $namespacePrefix;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function path($value): static
    {
        $this->_usedProperties['path'] = true;
        $this->path = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function namespacePrefix($value): static
    {
        $this->_usedProperties['namespacePrefix'] = true;
        $this->namespacePrefix = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('path', $value)) {
            $this->_usedProperties['path'] = true;
            $this->path = $value['path'];
            unset($value['path']);
        }

        if (array_key_exists('namespace_prefix', $value)) {
            $this->_usedProperties['namespacePrefix'] = true;
            $this->namespacePrefix = $value['namespace_prefix'];
            unset($value['namespace_prefix']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['path'])) {
            $output['path'] = $this->path;
        }
        if (isset($this->_usedProperties['namespacePrefix'])) {
            $output['namespace_prefix'] = $this->namespacePrefix;
        }

        return $output;
    }

}
