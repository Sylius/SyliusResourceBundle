<?php

namespace Symfony\Config\FosRest;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class BodyConverterConfig 
{
    private $enabled;
    private $validate;
    private $validationErrorsArgument;
    private $_usedProperties = [];

    /**
     * @default false
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
     * @default false
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function validate($value): static
    {
        $this->_usedProperties['validate'] = true;
        $this->validate = $value;

        return $this;
    }

    /**
     * @default 'validationErrors'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function validationErrorsArgument($value): static
    {
        $this->_usedProperties['validationErrorsArgument'] = true;
        $this->validationErrorsArgument = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('validate', $value)) {
            $this->_usedProperties['validate'] = true;
            $this->validate = $value['validate'];
            unset($value['validate']);
        }

        if (array_key_exists('validation_errors_argument', $value)) {
            $this->_usedProperties['validationErrorsArgument'] = true;
            $this->validationErrorsArgument = $value['validation_errors_argument'];
            unset($value['validation_errors_argument']);
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
        if (isset($this->_usedProperties['validate'])) {
            $output['validate'] = $this->validate;
        }
        if (isset($this->_usedProperties['validationErrorsArgument'])) {
            $output['validation_errors_argument'] = $this->validationErrorsArgument;
        }

        return $output;
    }

}
