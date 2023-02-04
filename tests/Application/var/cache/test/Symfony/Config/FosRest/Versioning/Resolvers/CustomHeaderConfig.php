<?php

namespace Symfony\Config\FosRest\Versioning\Resolvers;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class CustomHeaderConfig 
{
    private $enabled;
    private $headerName;
    private $_usedProperties = [];

    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enabled($value): static
    {
        $this->_usedProperties['enabled'] = true;
        $this->enabled = $value;

        return $this;
    }

    /**
     * @default 'X-Accept-Version'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function headerName($value): static
    {
        $this->_usedProperties['headerName'] = true;
        $this->headerName = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('header_name', $value)) {
            $this->_usedProperties['headerName'] = true;
            $this->headerName = $value['header_name'];
            unset($value['header_name']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['enabled'])) {
            $output['enabled'] = $this->enabled;
        }
        if (isset($this->_usedProperties['headerName'])) {
            $output['header_name'] = $this->headerName;
        }

        return $output;
    }

}
