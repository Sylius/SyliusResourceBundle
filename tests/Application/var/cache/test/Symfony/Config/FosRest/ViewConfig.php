<?php

namespace Symfony\Config\FosRest;

require_once __DIR__.\DIRECTORY_SEPARATOR.'View'.\DIRECTORY_SEPARATOR.'MimeTypesConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'View'.\DIRECTORY_SEPARATOR.'ViewResponseListenerConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'View'.\DIRECTORY_SEPARATOR.'JsonpHandlerConfig.php';

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Loader\ParamConfigurator;

/**
 * This class is automatically generated to help in creating a config.
 */
class ViewConfig 
{
    private $mimeTypes;
    private $formats;
    private $viewResponseListener;
    private $failedValidation;
    private $emptyContent;
    private $serializeNull;
    private $jsonpHandler;
    private $_usedProperties = [];

    /**
     * @template TValue
     * @param TValue $value
     * @default {"enabled":false,"service":null,"formats":[]}
     * @return \Symfony\Config\FosRest\View\MimeTypesConfig|$this
     * @psalm-return (TValue is array ? \Symfony\Config\FosRest\View\MimeTypesConfig : static)
     */
    public function mimeTypes(array $value = []): \Symfony\Config\FosRest\View\MimeTypesConfig|static
    {
        if (!\is_array($value)) {
            $this->_usedProperties['mimeTypes'] = true;
            $this->mimeTypes = $value;

            return $this;
        }

        if (!$this->mimeTypes instanceof \Symfony\Config\FosRest\View\MimeTypesConfig) {
            $this->_usedProperties['mimeTypes'] = true;
            $this->mimeTypes = new \Symfony\Config\FosRest\View\MimeTypesConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "mimeTypes()" has already been initialized. You cannot pass values the second time you call mimeTypes().');
        }

        return $this->mimeTypes;
    }

    /**
     * @return $this
     */
    public function format(string $name, ParamConfigurator|bool $value): static
    {
        $this->_usedProperties['formats'] = true;
        $this->formats[$name] = $value;

        return $this;
    }

    /**
     * @template TValue
     * @param TValue $value
     * @default {"enabled":false,"force":false,"service":null}
     * @return \Symfony\Config\FosRest\View\ViewResponseListenerConfig|$this
     * @psalm-return (TValue is array ? \Symfony\Config\FosRest\View\ViewResponseListenerConfig : static)
     */
    public function viewResponseListener(string|array $value = []): \Symfony\Config\FosRest\View\ViewResponseListenerConfig|static
    {
        if (!\is_array($value)) {
            $this->_usedProperties['viewResponseListener'] = true;
            $this->viewResponseListener = $value;

            return $this;
        }

        if (!$this->viewResponseListener instanceof \Symfony\Config\FosRest\View\ViewResponseListenerConfig) {
            $this->_usedProperties['viewResponseListener'] = true;
            $this->viewResponseListener = new \Symfony\Config\FosRest\View\ViewResponseListenerConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "viewResponseListener()" has already been initialized. You cannot pass values the second time you call viewResponseListener().');
        }

        return $this->viewResponseListener;
    }

    /**
     * @default 400
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function failedValidation($value): static
    {
        $this->_usedProperties['failedValidation'] = true;
        $this->failedValidation = $value;

        return $this;
    }

    /**
     * @default 204
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function emptyContent($value): static
    {
        $this->_usedProperties['emptyContent'] = true;
        $this->emptyContent = $value;

        return $this;
    }

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function serializeNull($value): static
    {
        $this->_usedProperties['serializeNull'] = true;
        $this->serializeNull = $value;

        return $this;
    }

    public function jsonpHandler(array $value = []): \Symfony\Config\FosRest\View\JsonpHandlerConfig
    {
        if (null === $this->jsonpHandler) {
            $this->_usedProperties['jsonpHandler'] = true;
            $this->jsonpHandler = new \Symfony\Config\FosRest\View\JsonpHandlerConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "jsonpHandler()" has already been initialized. You cannot pass values the second time you call jsonpHandler().');
        }

        return $this->jsonpHandler;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('mime_types', $value)) {
            $this->_usedProperties['mimeTypes'] = true;
            $this->mimeTypes = \is_array($value['mime_types']) ? new \Symfony\Config\FosRest\View\MimeTypesConfig($value['mime_types']) : $value['mime_types'];
            unset($value['mime_types']);
        }

        if (array_key_exists('formats', $value)) {
            $this->_usedProperties['formats'] = true;
            $this->formats = $value['formats'];
            unset($value['formats']);
        }

        if (array_key_exists('view_response_listener', $value)) {
            $this->_usedProperties['viewResponseListener'] = true;
            $this->viewResponseListener = \is_array($value['view_response_listener']) ? new \Symfony\Config\FosRest\View\ViewResponseListenerConfig($value['view_response_listener']) : $value['view_response_listener'];
            unset($value['view_response_listener']);
        }

        if (array_key_exists('failed_validation', $value)) {
            $this->_usedProperties['failedValidation'] = true;
            $this->failedValidation = $value['failed_validation'];
            unset($value['failed_validation']);
        }

        if (array_key_exists('empty_content', $value)) {
            $this->_usedProperties['emptyContent'] = true;
            $this->emptyContent = $value['empty_content'];
            unset($value['empty_content']);
        }

        if (array_key_exists('serialize_null', $value)) {
            $this->_usedProperties['serializeNull'] = true;
            $this->serializeNull = $value['serialize_null'];
            unset($value['serialize_null']);
        }

        if (array_key_exists('jsonp_handler', $value)) {
            $this->_usedProperties['jsonpHandler'] = true;
            $this->jsonpHandler = new \Symfony\Config\FosRest\View\JsonpHandlerConfig($value['jsonp_handler']);
            unset($value['jsonp_handler']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['mimeTypes'])) {
            $output['mime_types'] = $this->mimeTypes instanceof \Symfony\Config\FosRest\View\MimeTypesConfig ? $this->mimeTypes->toArray() : $this->mimeTypes;
        }
        if (isset($this->_usedProperties['formats'])) {
            $output['formats'] = $this->formats;
        }
        if (isset($this->_usedProperties['viewResponseListener'])) {
            $output['view_response_listener'] = $this->viewResponseListener instanceof \Symfony\Config\FosRest\View\ViewResponseListenerConfig ? $this->viewResponseListener->toArray() : $this->viewResponseListener;
        }
        if (isset($this->_usedProperties['failedValidation'])) {
            $output['failed_validation'] = $this->failedValidation;
        }
        if (isset($this->_usedProperties['emptyContent'])) {
            $output['empty_content'] = $this->emptyContent;
        }
        if (isset($this->_usedProperties['serializeNull'])) {
            $output['serialize_null'] = $this->serializeNull;
        }
        if (isset($this->_usedProperties['jsonpHandler'])) {
            $output['jsonp_handler'] = $this->jsonpHandler->toArray();
        }

        return $output;
    }

}
