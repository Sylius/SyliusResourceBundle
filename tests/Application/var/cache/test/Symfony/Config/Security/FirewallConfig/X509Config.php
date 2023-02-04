<?php

namespace Symfony\Config\Security\FirewallConfig;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class X509Config 
{
    private $provider;
    private $user;
    private $credentials;
    private $userIdentifier;
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
     * @default 'SSL_CLIENT_S_DN_Email'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function user($value): static
    {
        $this->_usedProperties['user'] = true;
        $this->user = $value;

        return $this;
    }

    /**
     * @default 'SSL_CLIENT_S_DN'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function credentials($value): static
    {
        $this->_usedProperties['credentials'] = true;
        $this->credentials = $value;

        return $this;
    }

    /**
     * @default 'emailAddress'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function userIdentifier($value): static
    {
        $this->_usedProperties['userIdentifier'] = true;
        $this->userIdentifier = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('provider', $value)) {
            $this->_usedProperties['provider'] = true;
            $this->provider = $value['provider'];
            unset($value['provider']);
        }

        if (array_key_exists('user', $value)) {
            $this->_usedProperties['user'] = true;
            $this->user = $value['user'];
            unset($value['user']);
        }

        if (array_key_exists('credentials', $value)) {
            $this->_usedProperties['credentials'] = true;
            $this->credentials = $value['credentials'];
            unset($value['credentials']);
        }

        if (array_key_exists('user_identifier', $value)) {
            $this->_usedProperties['userIdentifier'] = true;
            $this->userIdentifier = $value['user_identifier'];
            unset($value['user_identifier']);
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
        if (isset($this->_usedProperties['user'])) {
            $output['user'] = $this->user;
        }
        if (isset($this->_usedProperties['credentials'])) {
            $output['credentials'] = $this->credentials;
        }
        if (isset($this->_usedProperties['userIdentifier'])) {
            $output['user_identifier'] = $this->userIdentifier;
        }

        return $output;
    }

}
