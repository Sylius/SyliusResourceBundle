<?php

namespace Symfony\Config\JmsSerializer\Metadata\Warmup;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class PathsConfig 
{
    private $included;
    private $excluded;
    private $_usedProperties = [];

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function included(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['included'] = true;
        $this->included = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function excluded(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['excluded'] = true;
        $this->excluded = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('included', $value)) {
            $this->_usedProperties['included'] = true;
            $this->included = $value['included'];
            unset($value['included']);
        }

        if (array_key_exists('excluded', $value)) {
            $this->_usedProperties['excluded'] = true;
            $this->excluded = $value['excluded'];
            unset($value['excluded']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['included'])) {
            $output['included'] = $this->included;
        }
        if (isset($this->_usedProperties['excluded'])) {
            $output['excluded'] = $this->excluded;
        }

        return $output;
    }

}
