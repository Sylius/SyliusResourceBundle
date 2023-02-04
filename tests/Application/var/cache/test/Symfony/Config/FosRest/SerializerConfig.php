<?php

namespace Symfony\Config\FosRest;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class SerializerConfig 
{
    private $version;
    private $groups;
    private $serializeNull;
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
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function serializeNull($value): static
    {
        $this->_usedProperties['serializeNull'] = true;
        $this->serializeNull = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('version', $value)) {
            $this->_usedProperties['version'] = true;
            $this->version = $value['version'];
            unset($value['version']);
        }

        if (array_key_exists('groups', $value)) {
            $this->_usedProperties['groups'] = true;
            $this->groups = $value['groups'];
            unset($value['groups']);
        }

        if (array_key_exists('serialize_null', $value)) {
            $this->_usedProperties['serializeNull'] = true;
            $this->serializeNull = $value['serialize_null'];
            unset($value['serialize_null']);
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
        if (isset($this->_usedProperties['groups'])) {
            $output['groups'] = $this->groups;
        }
        if (isset($this->_usedProperties['serializeNull'])) {
            $output['serialize_null'] = $this->serializeNull;
        }

        return $output;
    }

}
