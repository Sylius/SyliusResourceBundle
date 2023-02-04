<?php

namespace Symfony\Config\Security\FirewallConfig;

require_once __DIR__.\DIRECTORY_SEPARATOR.'AccessToken'.\DIRECTORY_SEPARATOR.'TokenHandlerConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class AccessTokenConfig 
{
    private $provider;
    private $rememberMe;
    private $successHandler;
    private $failureHandler;
    private $realm;
    private $tokenExtractors;
    private $tokenHandler;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function provider($value): static
    {
        $this->_usedProperties['provider'] = true;
        $this->provider = $value;

        return $this;
    }

    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function rememberMe($value): static
    {
        $this->_usedProperties['rememberMe'] = true;
        $this->rememberMe = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function successHandler($value): static
    {
        $this->_usedProperties['successHandler'] = true;
        $this->successHandler = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function failureHandler($value): static
    {
        $this->_usedProperties['failureHandler'] = true;
        $this->failureHandler = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function realm($value): static
    {
        $this->_usedProperties['realm'] = true;
        $this->realm = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed>|string $value
     *
     * @return $this
     */
    public function tokenExtractors(ParamConfigurator|string|array $value): static
    {
        $this->_usedProperties['tokenExtractors'] = true;
        $this->tokenExtractors = $value;

        return $this;
    }

    /**
     * @template TValue
     * @param TValue $value
     * @example "App\\Security\\CustomTokenHandler"
     * @return \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandlerConfig|$this
     * @psalm-return (TValue is array ? \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandlerConfig : static)
     */
    public function tokenHandler(mixed $value = []): \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandlerConfig|static
    {
        if (!\is_array($value)) {
            $this->_usedProperties['tokenHandler'] = true;
            $this->tokenHandler = $value;

            return $this;
        }

        if (!$this->tokenHandler instanceof \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandlerConfig) {
            $this->_usedProperties['tokenHandler'] = true;
            $this->tokenHandler = new \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandlerConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "tokenHandler()" has already been initialized. You cannot pass values the second time you call tokenHandler().');
        }

        return $this->tokenHandler;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('provider', $value)) {
            $this->_usedProperties['provider'] = true;
            $this->provider = $value['provider'];
            unset($value['provider']);
        }

        if (array_key_exists('remember_me', $value)) {
            $this->_usedProperties['rememberMe'] = true;
            $this->rememberMe = $value['remember_me'];
            unset($value['remember_me']);
        }

        if (array_key_exists('success_handler', $value)) {
            $this->_usedProperties['successHandler'] = true;
            $this->successHandler = $value['success_handler'];
            unset($value['success_handler']);
        }

        if (array_key_exists('failure_handler', $value)) {
            $this->_usedProperties['failureHandler'] = true;
            $this->failureHandler = $value['failure_handler'];
            unset($value['failure_handler']);
        }

        if (array_key_exists('realm', $value)) {
            $this->_usedProperties['realm'] = true;
            $this->realm = $value['realm'];
            unset($value['realm']);
        }

        if (array_key_exists('token_extractors', $value)) {
            $this->_usedProperties['tokenExtractors'] = true;
            $this->tokenExtractors = $value['token_extractors'];
            unset($value['token_extractors']);
        }

        if (array_key_exists('token_handler', $value)) {
            $this->_usedProperties['tokenHandler'] = true;
            $this->tokenHandler = \is_array($value['token_handler']) ? new \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandlerConfig($value['token_handler']) : $value['token_handler'];
            unset($value['token_handler']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['provider'])) {
            $output['provider'] = $this->provider;
        }
        if (isset($this->_usedProperties['rememberMe'])) {
            $output['remember_me'] = $this->rememberMe;
        }
        if (isset($this->_usedProperties['successHandler'])) {
            $output['success_handler'] = $this->successHandler;
        }
        if (isset($this->_usedProperties['failureHandler'])) {
            $output['failure_handler'] = $this->failureHandler;
        }
        if (isset($this->_usedProperties['realm'])) {
            $output['realm'] = $this->realm;
        }
        if (isset($this->_usedProperties['tokenExtractors'])) {
            $output['token_extractors'] = $this->tokenExtractors;
        }
        if (isset($this->_usedProperties['tokenHandler'])) {
            $output['token_handler'] = $this->tokenHandler instanceof \Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandlerConfig ? $this->tokenHandler->toArray() : $this->tokenHandler;
        }

        return $output;
    }

}
