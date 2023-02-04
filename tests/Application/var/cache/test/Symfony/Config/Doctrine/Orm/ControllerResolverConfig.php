<?php

namespace Symfony\Config\Doctrine\Orm;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ControllerResolverConfig 
{
    private $enabled;
    private $autoMapping;
    private $evictCache;
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
     * Set to false to disable using route placeholders as lookup criteria when the primary key doesn't match the argument name
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function autoMapping($value): static
    {
        $this->_usedProperties['autoMapping'] = true;
        $this->autoMapping = $value;

        return $this;
    }

    /**
     * Set to true to fetch the entity from the database instead of using the cache, if any
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function evictCache($value): static
    {
        $this->_usedProperties['evictCache'] = true;
        $this->evictCache = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('auto_mapping', $value)) {
            $this->_usedProperties['autoMapping'] = true;
            $this->autoMapping = $value['auto_mapping'];
            unset($value['auto_mapping']);
        }

        if (array_key_exists('evict_cache', $value)) {
            $this->_usedProperties['evictCache'] = true;
            $this->evictCache = $value['evict_cache'];
            unset($value['evict_cache']);
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
        if (isset($this->_usedProperties['autoMapping'])) {
            $output['auto_mapping'] = $this->autoMapping;
        }
        if (isset($this->_usedProperties['evictCache'])) {
            $output['evict_cache'] = $this->evictCache;
        }

        return $output;
    }

}
