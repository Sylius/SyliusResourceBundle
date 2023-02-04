<?php

namespace Symfony\Config\Framework\Messenger;

require_once __DIR__.\DIRECTORY_SEPARATOR.'BusConfig'.\DIRECTORY_SEPARATOR.'DefaultMiddlewareConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'BusConfig'.\DIRECTORY_SEPARATOR.'MiddlewareConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class BusConfig 
{
    private $defaultMiddleware;
    private $middleware;
    private $_usedProperties = [];

    /**
     * @template TValue
     * @param TValue $value
     * @default {"enabled":true,"allow_no_handlers":false,"allow_no_senders":true}
     * @return \Symfony\Config\Framework\Messenger\BusConfig\DefaultMiddlewareConfig|$this
     * @psalm-return (TValue is array ? \Symfony\Config\Framework\Messenger\BusConfig\DefaultMiddlewareConfig : static)
     */
    public function defaultMiddleware(mixed $value = []): \Symfony\Config\Framework\Messenger\BusConfig\DefaultMiddlewareConfig|static
    {
        if (!\is_array($value)) {
            $this->_usedProperties['defaultMiddleware'] = true;
            $this->defaultMiddleware = $value;

            return $this;
        }

        if (!$this->defaultMiddleware instanceof \Symfony\Config\Framework\Messenger\BusConfig\DefaultMiddlewareConfig) {
            $this->_usedProperties['defaultMiddleware'] = true;
            $this->defaultMiddleware = new \Symfony\Config\Framework\Messenger\BusConfig\DefaultMiddlewareConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "defaultMiddleware()" has already been initialized. You cannot pass values the second time you call defaultMiddleware().');
        }

        return $this->defaultMiddleware;
    }

    /**
     * @template TValue
     * @param TValue $value
     * @return \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig|$this
     * @psalm-return (TValue is array ? \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig : static)
     */
    public function middleware(mixed $value = []): \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig|static
    {
        $this->_usedProperties['middleware'] = true;
        if (!\is_array($value)) {
            $this->middleware[] = $value;

            return $this;
        }

        return $this->middleware[] = new \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig($value);
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('default_middleware', $value)) {
            $this->_usedProperties['defaultMiddleware'] = true;
            $this->defaultMiddleware = \is_array($value['default_middleware']) ? new \Symfony\Config\Framework\Messenger\BusConfig\DefaultMiddlewareConfig($value['default_middleware']) : $value['default_middleware'];
            unset($value['default_middleware']);
        }

        if (array_key_exists('middleware', $value)) {
            $this->_usedProperties['middleware'] = true;
            $this->middleware = array_map(function ($v) { return \is_array($v) ? new \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig($v) : $v; }, $value['middleware']);
            unset($value['middleware']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['defaultMiddleware'])) {
            $output['default_middleware'] = $this->defaultMiddleware instanceof \Symfony\Config\Framework\Messenger\BusConfig\DefaultMiddlewareConfig ? $this->defaultMiddleware->toArray() : $this->defaultMiddleware;
        }
        if (isset($this->_usedProperties['middleware'])) {
            $output['middleware'] = array_map(function ($v) { return $v instanceof \Symfony\Config\Framework\Messenger\BusConfig\MiddlewareConfig ? $v->toArray() : $v; }, $this->middleware);
        }

        return $output;
    }

}
