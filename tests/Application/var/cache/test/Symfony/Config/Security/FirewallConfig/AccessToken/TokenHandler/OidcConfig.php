<?php

namespace Symfony\Config\Security\FirewallConfig\AccessToken\TokenHandler;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class OidcConfig 
{
    private $claim;
    private $audience;
    private $issuers;
    private $algorithm;
    private $key;
    private $_usedProperties = [];

    /**
     * Claim which contains the user identifier (e.g.: sub, email..).
     * @default 'sub'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function claim($value): static
    {
        $this->_usedProperties['claim'] = true;
        $this->claim = $value;

        return $this;
    }

    /**
     * Audience set in the token, for validation purpose.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function audience($value): static
    {
        $this->_usedProperties['audience'] = true;
        $this->audience = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function issuers(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['issuers'] = true;
        $this->issuers = $value;

        return $this;
    }

    /**
     * Algorithm used to sign the token.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function algorithm($value): static
    {
        $this->_usedProperties['algorithm'] = true;
        $this->algorithm = $value;

        return $this;
    }

    /**
     * JSON-encoded JWK used to sign the token (must contain a "kty" key).
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function key($value): static
    {
        $this->_usedProperties['key'] = true;
        $this->key = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('claim', $value)) {
            $this->_usedProperties['claim'] = true;
            $this->claim = $value['claim'];
            unset($value['claim']);
        }

        if (array_key_exists('audience', $value)) {
            $this->_usedProperties['audience'] = true;
            $this->audience = $value['audience'];
            unset($value['audience']);
        }

        if (array_key_exists('issuers', $value)) {
            $this->_usedProperties['issuers'] = true;
            $this->issuers = $value['issuers'];
            unset($value['issuers']);
        }

        if (array_key_exists('algorithm', $value)) {
            $this->_usedProperties['algorithm'] = true;
            $this->algorithm = $value['algorithm'];
            unset($value['algorithm']);
        }

        if (array_key_exists('key', $value)) {
            $this->_usedProperties['key'] = true;
            $this->key = $value['key'];
            unset($value['key']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['claim'])) {
            $output['claim'] = $this->claim;
        }
        if (isset($this->_usedProperties['audience'])) {
            $output['audience'] = $this->audience;
        }
        if (isset($this->_usedProperties['issuers'])) {
            $output['issuers'] = $this->issuers;
        }
        if (isset($this->_usedProperties['algorithm'])) {
            $output['algorithm'] = $this->algorithm;
        }
        if (isset($this->_usedProperties['key'])) {
            $output['key'] = $this->key;
        }

        return $output;
    }

}
