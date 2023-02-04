<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'BabdevPagerfanta'.\DIRECTORY_SEPARATOR.'ExceptionsStrategyConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class BabdevPagerfantaConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $defaultView;
    private $defaultTwigTemplate;
    private $exceptionsStrategy;
    private $_usedProperties = [];

    /**
     * @default 'default'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultView($value): static
    {
        $this->_usedProperties['defaultView'] = true;
        $this->defaultView = $value;

        return $this;
    }

    /**
     * @default '@Pagerfanta/default.html.twig'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultTwigTemplate($value): static
    {
        $this->_usedProperties['defaultTwigTemplate'] = true;
        $this->defaultTwigTemplate = $value;

        return $this;
    }

    /**
     * @default {"out_of_range_page":"to_http_not_found","not_valid_current_page":"to_http_not_found"}
    */
    public function exceptionsStrategy(array $value = []): \Symfony\Config\BabdevPagerfanta\ExceptionsStrategyConfig
    {
        if (null === $this->exceptionsStrategy) {
            $this->_usedProperties['exceptionsStrategy'] = true;
            $this->exceptionsStrategy = new \Symfony\Config\BabdevPagerfanta\ExceptionsStrategyConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "exceptionsStrategy()" has already been initialized. You cannot pass values the second time you call exceptionsStrategy().');
        }

        return $this->exceptionsStrategy;
    }

    public function getExtensionAlias(): string
    {
        return 'babdev_pagerfanta';
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('default_view', $value)) {
            $this->_usedProperties['defaultView'] = true;
            $this->defaultView = $value['default_view'];
            unset($value['default_view']);
        }

        if (array_key_exists('default_twig_template', $value)) {
            $this->_usedProperties['defaultTwigTemplate'] = true;
            $this->defaultTwigTemplate = $value['default_twig_template'];
            unset($value['default_twig_template']);
        }

        if (array_key_exists('exceptions_strategy', $value)) {
            $this->_usedProperties['exceptionsStrategy'] = true;
            $this->exceptionsStrategy = new \Symfony\Config\BabdevPagerfanta\ExceptionsStrategyConfig($value['exceptions_strategy']);
            unset($value['exceptions_strategy']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['defaultView'])) {
            $output['default_view'] = $this->defaultView;
        }
        if (isset($this->_usedProperties['defaultTwigTemplate'])) {
            $output['default_twig_template'] = $this->defaultTwigTemplate;
        }
        if (isset($this->_usedProperties['exceptionsStrategy'])) {
            $output['exceptions_strategy'] = $this->exceptionsStrategy->toArray();
        }

        return $output;
    }

}
