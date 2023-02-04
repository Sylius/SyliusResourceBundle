<?php

namespace Symfony\Config\JmsSerializer\Handlers;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class DatetimeConfig 
{
    private $defaultFormat;
    private $defaultTimezone;
    private $cdata;
    private $_usedProperties = [];

    /**
     * @default 'Y-m-d\\TH:i:sP'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultFormat($value): static
    {
        $this->_usedProperties['defaultFormat'] = true;
        $this->defaultFormat = $value;

        return $this;
    }

    /**
     * @default 'Europe/Berlin'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultTimezone($value): static
    {
        $this->_usedProperties['defaultTimezone'] = true;
        $this->defaultTimezone = $value;

        return $this;
    }

    /**
     * @default true
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cdata($value): static
    {
        $this->_usedProperties['cdata'] = true;
        $this->cdata = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('default_format', $value)) {
            $this->_usedProperties['defaultFormat'] = true;
            $this->defaultFormat = $value['default_format'];
            unset($value['default_format']);
        }

        if (array_key_exists('default_timezone', $value)) {
            $this->_usedProperties['defaultTimezone'] = true;
            $this->defaultTimezone = $value['default_timezone'];
            unset($value['default_timezone']);
        }

        if (array_key_exists('cdata', $value)) {
            $this->_usedProperties['cdata'] = true;
            $this->cdata = $value['cdata'];
            unset($value['cdata']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['defaultFormat'])) {
            $output['default_format'] = $this->defaultFormat;
        }
        if (isset($this->_usedProperties['defaultTimezone'])) {
            $output['default_timezone'] = $this->defaultTimezone;
        }
        if (isset($this->_usedProperties['cdata'])) {
            $output['cdata'] = $this->cdata;
        }

        return $output;
    }

}
