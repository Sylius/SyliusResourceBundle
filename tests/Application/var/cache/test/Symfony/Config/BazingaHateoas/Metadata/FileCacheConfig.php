<?php

namespace Symfony\Config\BazingaHateoas\Metadata;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class FileCacheConfig 
{
    private $dir;
    private $_usedProperties = [];

    /**
     * @default '%kernel.cache_dir%/hateoas'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function dir($value): static
    {
        $this->_usedProperties['dir'] = true;
        $this->dir = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('dir', $value)) {
            $this->_usedProperties['dir'] = true;
            $this->dir = $value['dir'];
            unset($value['dir']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['dir'])) {
            $output['dir'] = $this->dir;
        }

        return $output;
    }

}
