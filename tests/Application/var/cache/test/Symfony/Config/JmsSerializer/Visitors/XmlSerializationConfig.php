<?php

namespace Symfony\Config\JmsSerializer\Visitors;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class XmlSerializationConfig 
{
    private $version;
    private $encoding;
    private $formatOutput;
    private $defaultRootName;
    private $defaultRootNs;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function version($value): static
    {
        $this->_usedProperties['version'] = true;
        $this->version = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function encoding($value): static
    {
        $this->_usedProperties['encoding'] = true;
        $this->encoding = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function formatOutput($value): static
    {
        $this->_usedProperties['formatOutput'] = true;
        $this->formatOutput = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultRootName($value): static
    {
        $this->_usedProperties['defaultRootName'] = true;
        $this->defaultRootName = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultRootNs($value): static
    {
        $this->_usedProperties['defaultRootNs'] = true;
        $this->defaultRootNs = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('version', $value)) {
            $this->_usedProperties['version'] = true;
            $this->version = $value['version'];
            unset($value['version']);
        }

        if (array_key_exists('encoding', $value)) {
            $this->_usedProperties['encoding'] = true;
            $this->encoding = $value['encoding'];
            unset($value['encoding']);
        }

        if (array_key_exists('format_output', $value)) {
            $this->_usedProperties['formatOutput'] = true;
            $this->formatOutput = $value['format_output'];
            unset($value['format_output']);
        }

        if (array_key_exists('default_root_name', $value)) {
            $this->_usedProperties['defaultRootName'] = true;
            $this->defaultRootName = $value['default_root_name'];
            unset($value['default_root_name']);
        }

        if (array_key_exists('default_root_ns', $value)) {
            $this->_usedProperties['defaultRootNs'] = true;
            $this->defaultRootNs = $value['default_root_ns'];
            unset($value['default_root_ns']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['version'])) {
            $output['version'] = $this->version;
        }
        if (isset($this->_usedProperties['encoding'])) {
            $output['encoding'] = $this->encoding;
        }
        if (isset($this->_usedProperties['formatOutput'])) {
            $output['format_output'] = $this->formatOutput;
        }
        if (isset($this->_usedProperties['defaultRootName'])) {
            $output['default_root_name'] = $this->defaultRootName;
        }
        if (isset($this->_usedProperties['defaultRootNs'])) {
            $output['default_root_ns'] = $this->defaultRootNs;
        }

        return $output;
    }

}
