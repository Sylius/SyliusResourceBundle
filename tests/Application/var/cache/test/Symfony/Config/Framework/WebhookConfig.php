<?php

namespace Symfony\Config\Framework;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Webhook'.\DIRECTORY_SEPARATOR.'RoutingConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class WebhookConfig 
{
    private $enabled;
    private $messageBus;
    private $routing;
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
     * The message bus to use.
     * @default 'messenger.default_bus'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function messageBus($value): static
    {
        $this->_usedProperties['messageBus'] = true;
        $this->messageBus = $value;

        return $this;
    }

    public function routing(string $type, array $value = []): \Symfony\Config\Framework\Webhook\RoutingConfig
    {
        if (!isset($this->routing[$type])) {
            $this->_usedProperties['routing'] = true;
            $this->routing[$type] = new \Symfony\Config\Framework\Webhook\RoutingConfig($value);
        } elseif (1 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "routing()" has already been initialized. You cannot pass values the second time you call routing().');
        }

        return $this->routing[$type];
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('message_bus', $value)) {
            $this->_usedProperties['messageBus'] = true;
            $this->messageBus = $value['message_bus'];
            unset($value['message_bus']);
        }

        if (array_key_exists('routing', $value)) {
            $this->_usedProperties['routing'] = true;
            $this->routing = array_map(function ($v) { return new \Symfony\Config\Framework\Webhook\RoutingConfig($v); }, $value['routing']);
            unset($value['routing']);
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
        if (isset($this->_usedProperties['messageBus'])) {
            $output['message_bus'] = $this->messageBus;
        }
        if (isset($this->_usedProperties['routing'])) {
            $output['routing'] = array_map(function ($v) { return $v->toArray(); }, $this->routing);
        }

        return $output;
    }

}
