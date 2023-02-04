<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'BazingaHateoas'.\DIRECTORY_SEPARATOR.'MetadataConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'BazingaHateoas'.\DIRECTORY_SEPARATOR.'SerializerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'BazingaHateoas'.\DIRECTORY_SEPARATOR.'TwigExtensionConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class BazingaHateoasConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $metadata;
    private $serializer;
    private $twigExtension;
    private $_usedProperties = [];

    /**
     * @default {"cache":"file","file_cache":{"dir":"%kernel.cache_dir%\/hateoas"}}
    */
    public function metadata(array $value = []): \Symfony\Config\BazingaHateoas\MetadataConfig
    {
        if (null === $this->metadata) {
            $this->_usedProperties['metadata'] = true;
            $this->metadata = new \Symfony\Config\BazingaHateoas\MetadataConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "metadata()" has already been initialized. You cannot pass values the second time you call metadata().');
        }

        return $this->metadata;
    }

    /**
     * @default {"json":"hateoas.serializer.json_hal","xml":"hateoas.serializer.xml"}
    */
    public function serializer(array $value = []): \Symfony\Config\BazingaHateoas\SerializerConfig
    {
        if (null === $this->serializer) {
            $this->_usedProperties['serializer'] = true;
            $this->serializer = new \Symfony\Config\BazingaHateoas\SerializerConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "serializer()" has already been initialized. You cannot pass values the second time you call serializer().');
        }

        return $this->serializer;
    }

    /**
     * @default {"enabled":true}
    */
    public function twigExtension(array $value = []): \Symfony\Config\BazingaHateoas\TwigExtensionConfig
    {
        if (null === $this->twigExtension) {
            $this->_usedProperties['twigExtension'] = true;
            $this->twigExtension = new \Symfony\Config\BazingaHateoas\TwigExtensionConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "twigExtension()" has already been initialized. You cannot pass values the second time you call twigExtension().');
        }

        return $this->twigExtension;
    }

    public function getExtensionAlias(): string
    {
        return 'bazinga_hateoas';
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('metadata', $value)) {
            $this->_usedProperties['metadata'] = true;
            $this->metadata = new \Symfony\Config\BazingaHateoas\MetadataConfig($value['metadata']);
            unset($value['metadata']);
        }

        if (array_key_exists('serializer', $value)) {
            $this->_usedProperties['serializer'] = true;
            $this->serializer = new \Symfony\Config\BazingaHateoas\SerializerConfig($value['serializer']);
            unset($value['serializer']);
        }

        if (array_key_exists('twig_extension', $value)) {
            $this->_usedProperties['twigExtension'] = true;
            $this->twigExtension = new \Symfony\Config\BazingaHateoas\TwigExtensionConfig($value['twig_extension']);
            unset($value['twig_extension']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['metadata'])) {
            $output['metadata'] = $this->metadata->toArray();
        }
        if (isset($this->_usedProperties['serializer'])) {
            $output['serializer'] = $this->serializer->toArray();
        }
        if (isset($this->_usedProperties['twigExtension'])) {
            $output['twig_extension'] = $this->twigExtension->toArray();
        }

        return $output;
    }

}
