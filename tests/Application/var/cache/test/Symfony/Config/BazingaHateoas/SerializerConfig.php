<?php

namespace Symfony\Config\BazingaHateoas;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class SerializerConfig 
{
    private $json;
    private $xml;
    private $_usedProperties = [];

    /**
     * @default 'hateoas.serializer.json_hal'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function json($value): static
    {
        $this->_usedProperties['json'] = true;
        $this->json = $value;

        return $this;
    }

    /**
     * @default 'hateoas.serializer.xml'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function xml($value): static
    {
        $this->_usedProperties['xml'] = true;
        $this->xml = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('json', $value)) {
            $this->_usedProperties['json'] = true;
            $this->json = $value['json'];
            unset($value['json']);
        }

        if (array_key_exists('xml', $value)) {
            $this->_usedProperties['xml'] = true;
            $this->xml = $value['xml'];
            unset($value['xml']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['json'])) {
            $output['json'] = $this->json;
        }
        if (isset($this->_usedProperties['xml'])) {
            $output['xml'] = $this->xml;
        }

        return $output;
    }

}
