<?php

namespace Symfony\Config\JmsSerializer\DefaultContext;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class DeserializationConfig 
{
    private $id;
    private $serializeNull;
    private $enableMaxDepthChecks;
    private $attributes;
    private $groups;
    private $version;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function id($value): static
    {
        $this->_usedProperties['id'] = true;
        $this->id = $value;

        return $this;
    }

    /**
     * Flag if null values should be serialized
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function serializeNull($value): static
    {
        $this->_usedProperties['serializeNull'] = true;
        $this->serializeNull = $value;

        return $this;
    }

    /**
     * Flag to enable the max-depth exclusion strategy
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function enableMaxDepthChecks($value): static
    {
        $this->_usedProperties['enableMaxDepthChecks'] = true;
        $this->enableMaxDepthChecks = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function attributes(string $key, mixed $value): static
    {
        $this->_usedProperties['attributes'] = true;
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function groups(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['groups'] = true;
        $this->groups = $value;

        return $this;
    }

    /**
     * Application version to use in exclusion strategies
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

    public function __construct(array $value = [])
    {
        if (array_key_exists('id', $value)) {
            $this->_usedProperties['id'] = true;
            $this->id = $value['id'];
            unset($value['id']);
        }

        if (array_key_exists('serialize_null', $value)) {
            $this->_usedProperties['serializeNull'] = true;
            $this->serializeNull = $value['serialize_null'];
            unset($value['serialize_null']);
        }

        if (array_key_exists('enable_max_depth_checks', $value)) {
            $this->_usedProperties['enableMaxDepthChecks'] = true;
            $this->enableMaxDepthChecks = $value['enable_max_depth_checks'];
            unset($value['enable_max_depth_checks']);
        }

        if (array_key_exists('attributes', $value)) {
            $this->_usedProperties['attributes'] = true;
            $this->attributes = $value['attributes'];
            unset($value['attributes']);
        }

        if (array_key_exists('groups', $value)) {
            $this->_usedProperties['groups'] = true;
            $this->groups = $value['groups'];
            unset($value['groups']);
        }

        if (array_key_exists('version', $value)) {
            $this->_usedProperties['version'] = true;
            $this->version = $value['version'];
            unset($value['version']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['id'])) {
            $output['id'] = $this->id;
        }
        if (isset($this->_usedProperties['serializeNull'])) {
            $output['serialize_null'] = $this->serializeNull;
        }
        if (isset($this->_usedProperties['enableMaxDepthChecks'])) {
            $output['enable_max_depth_checks'] = $this->enableMaxDepthChecks;
        }
        if (isset($this->_usedProperties['attributes'])) {
            $output['attributes'] = $this->attributes;
        }
        if (isset($this->_usedProperties['groups'])) {
            $output['groups'] = $this->groups;
        }
        if (isset($this->_usedProperties['version'])) {
            $output['version'] = $this->version;
        }

        return $output;
    }

}
