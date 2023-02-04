<?php

namespace Symfony\Config\FosRest\View;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class JsonpHandlerConfig 
{
    private $callbackParam;
    private $mimeType;
    private $_usedProperties = [];

    /**
     * @default 'callback'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function callbackParam($value): static
    {
        $this->_usedProperties['callbackParam'] = true;
        $this->callbackParam = $value;

        return $this;
    }

    /**
     * @default 'application/javascript+jsonp'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function mimeType($value): static
    {
        $this->_usedProperties['mimeType'] = true;
        $this->mimeType = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('callback_param', $value)) {
            $this->_usedProperties['callbackParam'] = true;
            $this->callbackParam = $value['callback_param'];
            unset($value['callback_param']);
        }

        if (array_key_exists('mime_type', $value)) {
            $this->_usedProperties['mimeType'] = true;
            $this->mimeType = $value['mime_type'];
            unset($value['mime_type']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['callbackParam'])) {
            $output['callback_param'] = $this->callbackParam;
        }
        if (isset($this->_usedProperties['mimeType'])) {
            $output['mime_type'] = $this->mimeType;
        }

        return $output;
    }

}
