<?php

namespace Symfony\Config\FosRest;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ServiceConfig 
{
    private $serializer;
    private $viewHandler;
    private $validator;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function serializer($value): static
    {
        $this->_usedProperties['serializer'] = true;
        $this->serializer = $value;

        return $this;
    }

    /**
     * @default 'fos_rest.view_handler.default'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function viewHandler($value): static
    {
        $this->_usedProperties['viewHandler'] = true;
        $this->viewHandler = $value;

        return $this;
    }

    /**
     * @default 'validator'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function validator($value): static
    {
        $this->_usedProperties['validator'] = true;
        $this->validator = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('serializer', $value)) {
            $this->_usedProperties['serializer'] = true;
            $this->serializer = $value['serializer'];
            unset($value['serializer']);
        }

        if (array_key_exists('view_handler', $value)) {
            $this->_usedProperties['viewHandler'] = true;
            $this->viewHandler = $value['view_handler'];
            unset($value['view_handler']);
        }

        if (array_key_exists('validator', $value)) {
            $this->_usedProperties['validator'] = true;
            $this->validator = $value['validator'];
            unset($value['validator']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['serializer'])) {
            $output['serializer'] = $this->serializer;
        }
        if (isset($this->_usedProperties['viewHandler'])) {
            $output['view_handler'] = $this->viewHandler;
        }
        if (isset($this->_usedProperties['validator'])) {
            $output['validator'] = $this->validator;
        }

        return $output;
    }

}
