<?php

namespace Symfony\Config\FosRest;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Versioning'.\DIRECTORY_SEPARATOR.'ResolversConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class VersioningConfig 
{
    private $enabled;
    private $defaultVersion;
    private $resolvers;
    private $guessingOrder;
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
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultVersion($value): static
    {
        $this->_usedProperties['defaultVersion'] = true;
        $this->defaultVersion = $value;

        return $this;
    }

    /**
     * @default {"query":{"enabled":true,"parameter_name":"version"},"custom_header":{"enabled":true,"header_name":"X-Accept-Version"},"media_type":{"enabled":true,"regex":"\/(v|version)=(?P<version>[0-9\\.]+)\/"}}
    */
    public function resolvers(array $value = []): \Symfony\Config\FosRest\Versioning\ResolversConfig
    {
        if (null === $this->resolvers) {
            $this->_usedProperties['resolvers'] = true;
            $this->resolvers = new \Symfony\Config\FosRest\Versioning\ResolversConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "resolvers()" has already been initialized. You cannot pass values the second time you call resolvers().');
        }

        return $this->resolvers;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function guessingOrder(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['guessingOrder'] = true;
        $this->guessingOrder = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('default_version', $value)) {
            $this->_usedProperties['defaultVersion'] = true;
            $this->defaultVersion = $value['default_version'];
            unset($value['default_version']);
        }

        if (array_key_exists('resolvers', $value)) {
            $this->_usedProperties['resolvers'] = true;
            $this->resolvers = new \Symfony\Config\FosRest\Versioning\ResolversConfig($value['resolvers']);
            unset($value['resolvers']);
        }

        if (array_key_exists('guessing_order', $value)) {
            $this->_usedProperties['guessingOrder'] = true;
            $this->guessingOrder = $value['guessing_order'];
            unset($value['guessing_order']);
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
        if (isset($this->_usedProperties['defaultVersion'])) {
            $output['default_version'] = $this->defaultVersion;
        }
        if (isset($this->_usedProperties['resolvers'])) {
            $output['resolvers'] = $this->resolvers->toArray();
        }
        if (isset($this->_usedProperties['guessingOrder'])) {
            $output['guessing_order'] = $this->guessingOrder;
        }

        return $output;
    }

}
