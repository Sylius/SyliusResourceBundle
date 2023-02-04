<?php

namespace Symfony\Config\FosRest\Versioning;

require_once __DIR__.\DIRECTORY_SEPARATOR.'Resolvers'.\DIRECTORY_SEPARATOR.'QueryConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Resolvers'.\DIRECTORY_SEPARATOR.'CustomHeaderConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'Resolvers'.\DIRECTORY_SEPARATOR.'MediaTypeConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ResolversConfig 
{
    private $query;
    private $customHeader;
    private $mediaType;
    private $_usedProperties = [];

    /**
     * @default {"enabled":true,"parameter_name":"version"}
    */
    public function query(array $value = []): \Symfony\Config\FosRest\Versioning\Resolvers\QueryConfig
    {
        if (null === $this->query) {
            $this->_usedProperties['query'] = true;
            $this->query = new \Symfony\Config\FosRest\Versioning\Resolvers\QueryConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "query()" has already been initialized. You cannot pass values the second time you call query().');
        }

        return $this->query;
    }

    /**
     * @default {"enabled":true,"header_name":"X-Accept-Version"}
    */
    public function customHeader(array $value = []): \Symfony\Config\FosRest\Versioning\Resolvers\CustomHeaderConfig
    {
        if (null === $this->customHeader) {
            $this->_usedProperties['customHeader'] = true;
            $this->customHeader = new \Symfony\Config\FosRest\Versioning\Resolvers\CustomHeaderConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "customHeader()" has already been initialized. You cannot pass values the second time you call customHeader().');
        }

        return $this->customHeader;
    }

    /**
     * @default {"enabled":true,"regex":"\/(v|version)=(?P<version>[0-9\\.]+)\/"}
    */
    public function mediaType(array $value = []): \Symfony\Config\FosRest\Versioning\Resolvers\MediaTypeConfig
    {
        if (null === $this->mediaType) {
            $this->_usedProperties['mediaType'] = true;
            $this->mediaType = new \Symfony\Config\FosRest\Versioning\Resolvers\MediaTypeConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "mediaType()" has already been initialized. You cannot pass values the second time you call mediaType().');
        }

        return $this->mediaType;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('query', $value)) {
            $this->_usedProperties['query'] = true;
            $this->query = new \Symfony\Config\FosRest\Versioning\Resolvers\QueryConfig($value['query']);
            unset($value['query']);
        }

        if (array_key_exists('custom_header', $value)) {
            $this->_usedProperties['customHeader'] = true;
            $this->customHeader = new \Symfony\Config\FosRest\Versioning\Resolvers\CustomHeaderConfig($value['custom_header']);
            unset($value['custom_header']);
        }

        if (array_key_exists('media_type', $value)) {
            $this->_usedProperties['mediaType'] = true;
            $this->mediaType = new \Symfony\Config\FosRest\Versioning\Resolvers\MediaTypeConfig($value['media_type']);
            unset($value['media_type']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['query'])) {
            $output['query'] = $this->query->toArray();
        }
        if (isset($this->_usedProperties['customHeader'])) {
            $output['custom_header'] = $this->customHeader->toArray();
        }
        if (isset($this->_usedProperties['mediaType'])) {
            $output['media_type'] = $this->mediaType->toArray();
        }

        return $output;
    }

}
