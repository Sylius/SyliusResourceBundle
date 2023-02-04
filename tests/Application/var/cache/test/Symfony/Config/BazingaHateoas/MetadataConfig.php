<?php

namespace Symfony\Config\BazingaHateoas;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Metadata'.\DIRECTORY_SEPARATOR.'FileCacheConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class MetadataConfig 
{
    private $cache;
    private $fileCache;
    private $_usedProperties = [];

    /**
     * @default 'file'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function cache($value): static
    {
        $this->_usedProperties['cache'] = true;
        $this->cache = $value;

        return $this;
    }

    /**
     * @default {"dir":"%kernel.cache_dir%\/hateoas"}
    */
    public function fileCache(array $value = []): \Symfony\Config\BazingaHateoas\Metadata\FileCacheConfig
    {
        if (null === $this->fileCache) {
            $this->_usedProperties['fileCache'] = true;
            $this->fileCache = new \Symfony\Config\BazingaHateoas\Metadata\FileCacheConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "fileCache()" has already been initialized. You cannot pass values the second time you call fileCache().');
        }

        return $this->fileCache;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('cache', $value)) {
            $this->_usedProperties['cache'] = true;
            $this->cache = $value['cache'];
            unset($value['cache']);
        }

        if (array_key_exists('file_cache', $value)) {
            $this->_usedProperties['fileCache'] = true;
            $this->fileCache = new \Symfony\Config\BazingaHateoas\Metadata\FileCacheConfig($value['file_cache']);
            unset($value['file_cache']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['cache'])) {
            $output['cache'] = $this->cache;
        }
        if (isset($this->_usedProperties['fileCache'])) {
            $output['file_cache'] = $this->fileCache->toArray();
        }

        return $output;
    }

}
