<?php

namespace Symfony\Config\FosRest\FormatListener;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class RuleConfig 
{
    private $path;
    private $host;
    private $methods;
    private $attributes;
    private $stop;
    private $preferExtension;
    private $fallbackFormat;
    private $priorities;
    private $_usedProperties = [];

    /**
     * URL path info
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function path($value): static
    {
        $this->_usedProperties['path'] = true;
        $this->path = $value;

        return $this;
    }

    /**
     * URL host name
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function host($value): static
    {
        $this->_usedProperties['host'] = true;
        $this->host = $value;

        return $this;
    }

    /**
     * Method for URL
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function methods(mixed $value = NULL): static
    {
        $this->_usedProperties['methods'] = true;
        $this->methods = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function attribute(string $name, mixed $value): static
    {
        $this->_usedProperties['attributes'] = true;
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function stop($value): static
    {
        $this->_usedProperties['stop'] = true;
        $this->stop = $value;

        return $this;
    }

    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function preferExtension($value): static
    {
        $this->_usedProperties['preferExtension'] = true;
        $this->preferExtension = $value;

        return $this;
    }

    /**
     * @default 'html'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function fallbackFormat($value): static
    {
        $this->_usedProperties['fallbackFormat'] = true;
        $this->fallbackFormat = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed>|string $value
     *
     * @return $this
     */
    public function priorities(ParamConfigurator|string|array $value): static
    {
        $this->_usedProperties['priorities'] = true;
        $this->priorities = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('path', $value)) {
            $this->_usedProperties['path'] = true;
            $this->path = $value['path'];
            unset($value['path']);
        }

        if (array_key_exists('host', $value)) {
            $this->_usedProperties['host'] = true;
            $this->host = $value['host'];
            unset($value['host']);
        }

        if (array_key_exists('methods', $value)) {
            $this->_usedProperties['methods'] = true;
            $this->methods = $value['methods'];
            unset($value['methods']);
        }

        if (array_key_exists('attributes', $value)) {
            $this->_usedProperties['attributes'] = true;
            $this->attributes = $value['attributes'];
            unset($value['attributes']);
        }

        if (array_key_exists('stop', $value)) {
            $this->_usedProperties['stop'] = true;
            $this->stop = $value['stop'];
            unset($value['stop']);
        }

        if (array_key_exists('prefer_extension', $value)) {
            $this->_usedProperties['preferExtension'] = true;
            $this->preferExtension = $value['prefer_extension'];
            unset($value['prefer_extension']);
        }

        if (array_key_exists('fallback_format', $value)) {
            $this->_usedProperties['fallbackFormat'] = true;
            $this->fallbackFormat = $value['fallback_format'];
            unset($value['fallback_format']);
        }

        if (array_key_exists('priorities', $value)) {
            $this->_usedProperties['priorities'] = true;
            $this->priorities = $value['priorities'];
            unset($value['priorities']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['path'])) {
            $output['path'] = $this->path;
        }
        if (isset($this->_usedProperties['host'])) {
            $output['host'] = $this->host;
        }
        if (isset($this->_usedProperties['methods'])) {
            $output['methods'] = $this->methods;
        }
        if (isset($this->_usedProperties['attributes'])) {
            $output['attributes'] = $this->attributes;
        }
        if (isset($this->_usedProperties['stop'])) {
            $output['stop'] = $this->stop;
        }
        if (isset($this->_usedProperties['preferExtension'])) {
            $output['prefer_extension'] = $this->preferExtension;
        }
        if (isset($this->_usedProperties['fallbackFormat'])) {
            $output['fallback_format'] = $this->fallbackFormat;
        }
        if (isset($this->_usedProperties['priorities'])) {
            $output['priorities'] = $this->priorities;
        }

        return $output;
    }

}
