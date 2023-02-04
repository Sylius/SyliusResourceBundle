<?php

namespace Symfony\Config\JmsSerializer;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Handlers'.\DIRECTORY_SEPARATOR.'DatetimeConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Handlers'.\DIRECTORY_SEPARATOR.'ArrayCollectionConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Handlers'.\DIRECTORY_SEPARATOR.'SymfonyUidConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class HandlersConfig 
{
    private $datetime;
    private $arrayCollection;
    private $symfonyUid;
    private $_usedProperties = [];

    /**
     * @default {"default_format":"Y-m-d\\TH:i:sP","default_timezone":"Europe\/Berlin","cdata":true}
    */
    public function datetime(array $value = []): \Symfony\Config\JmsSerializer\Handlers\DatetimeConfig
    {
        if (null === $this->datetime) {
            $this->_usedProperties['datetime'] = true;
            $this->datetime = new \Symfony\Config\JmsSerializer\Handlers\DatetimeConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "datetime()" has already been initialized. You cannot pass values the second time you call datetime().');
        }

        return $this->datetime;
    }

    /**
     * @default {"initialize_excluded":false}
    */
    public function arrayCollection(array $value = []): \Symfony\Config\JmsSerializer\Handlers\ArrayCollectionConfig
    {
        if (null === $this->arrayCollection) {
            $this->_usedProperties['arrayCollection'] = true;
            $this->arrayCollection = new \Symfony\Config\JmsSerializer\Handlers\ArrayCollectionConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "arrayCollection()" has already been initialized. You cannot pass values the second time you call arrayCollection().');
        }

        return $this->arrayCollection;
    }

    /**
     * @default {"default_format":"canonical","cdata":true}
    */
    public function symfonyUid(array $value = []): \Symfony\Config\JmsSerializer\Handlers\SymfonyUidConfig
    {
        if (null === $this->symfonyUid) {
            $this->_usedProperties['symfonyUid'] = true;
            $this->symfonyUid = new \Symfony\Config\JmsSerializer\Handlers\SymfonyUidConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "symfonyUid()" has already been initialized. You cannot pass values the second time you call symfonyUid().');
        }

        return $this->symfonyUid;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('datetime', $value)) {
            $this->_usedProperties['datetime'] = true;
            $this->datetime = new \Symfony\Config\JmsSerializer\Handlers\DatetimeConfig($value['datetime']);
            unset($value['datetime']);
        }

        if (array_key_exists('array_collection', $value)) {
            $this->_usedProperties['arrayCollection'] = true;
            $this->arrayCollection = new \Symfony\Config\JmsSerializer\Handlers\ArrayCollectionConfig($value['array_collection']);
            unset($value['array_collection']);
        }

        if (array_key_exists('symfony_uid', $value)) {
            $this->_usedProperties['symfonyUid'] = true;
            $this->symfonyUid = new \Symfony\Config\JmsSerializer\Handlers\SymfonyUidConfig($value['symfony_uid']);
            unset($value['symfony_uid']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['datetime'])) {
            $output['datetime'] = $this->datetime->toArray();
        }
        if (isset($this->_usedProperties['arrayCollection'])) {
            $output['array_collection'] = $this->arrayCollection->toArray();
        }
        if (isset($this->_usedProperties['symfonyUid'])) {
            $output['symfony_uid'] = $this->symfonyUid->toArray();
        }

        return $output;
    }

}
