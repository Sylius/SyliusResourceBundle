<?php

namespace Symfony\Config\Framework\Messenger\BusConfig;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class DefaultMiddlewareConfig 
{
    private $enabled;
    private $allowNoHandlers;
    private $allowNoSenders;
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
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function allowNoHandlers($value): static
    {
        $this->_usedProperties['allowNoHandlers'] = true;
        $this->allowNoHandlers = $value;

        return $this;
    }

    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function allowNoSenders($value): static
    {
        $this->_usedProperties['allowNoSenders'] = true;
        $this->allowNoSenders = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('allow_no_handlers', $value)) {
            $this->_usedProperties['allowNoHandlers'] = true;
            $this->allowNoHandlers = $value['allow_no_handlers'];
            unset($value['allow_no_handlers']);
        }

        if (array_key_exists('allow_no_senders', $value)) {
            $this->_usedProperties['allowNoSenders'] = true;
            $this->allowNoSenders = $value['allow_no_senders'];
            unset($value['allow_no_senders']);
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
        if (isset($this->_usedProperties['allowNoHandlers'])) {
            $output['allow_no_handlers'] = $this->allowNoHandlers;
        }
        if (isset($this->_usedProperties['allowNoSenders'])) {
            $output['allow_no_senders'] = $this->allowNoSenders;
        }

        return $output;
    }

}
