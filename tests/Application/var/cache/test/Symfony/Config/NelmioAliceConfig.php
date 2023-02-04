<?php

namespace Symfony\Config;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class NelmioAliceConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $locale;
    private $seed;
    private $functionsBlacklist;
    private $loadingLimit;
    private $maxUniqueValuesRetry;
    private $_usedProperties = [];

    /**
     * Default locale for the Faker Generator
     * @default 'en_US'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function locale($value): static
    {
        $this->_usedProperties['locale'] = true;
        $this->locale = $value;

        return $this;
    }

    /**
     * Value used make sure Faker generates data consistently across runs, set to null to disable.
     * @default 1
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function seed($value): static
    {
        $this->_usedProperties['seed'] = true;
        $this->seed = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function functionsBlacklist(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['functionsBlacklist'] = true;
        $this->functionsBlacklist = $value;

        return $this;
    }

    /**
     * Alice may do some recursion to resolve certain values. This parameter defines a limit which will stop the resolution once reached.
     * @default 5
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function loadingLimit($value): static
    {
        $this->_usedProperties['loadingLimit'] = true;
        $this->loadingLimit = $value;

        return $this;
    }

    /**
     * Maximum number of time Alice can try to generate a unique value before stopping and failing.
     * @default 150
     * @param ParamConfigurator|int $value
     * @return $this
     */
    public function maxUniqueValuesRetry($value): static
    {
        $this->_usedProperties['maxUniqueValuesRetry'] = true;
        $this->maxUniqueValuesRetry = $value;

        return $this;
    }

    public function getExtensionAlias(): string
    {
        return 'nelmio_alice';
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('locale', $value)) {
            $this->_usedProperties['locale'] = true;
            $this->locale = $value['locale'];
            unset($value['locale']);
        }

        if (array_key_exists('seed', $value)) {
            $this->_usedProperties['seed'] = true;
            $this->seed = $value['seed'];
            unset($value['seed']);
        }

        if (array_key_exists('functions_blacklist', $value)) {
            $this->_usedProperties['functionsBlacklist'] = true;
            $this->functionsBlacklist = $value['functions_blacklist'];
            unset($value['functions_blacklist']);
        }

        if (array_key_exists('loading_limit', $value)) {
            $this->_usedProperties['loadingLimit'] = true;
            $this->loadingLimit = $value['loading_limit'];
            unset($value['loading_limit']);
        }

        if (array_key_exists('max_unique_values_retry', $value)) {
            $this->_usedProperties['maxUniqueValuesRetry'] = true;
            $this->maxUniqueValuesRetry = $value['max_unique_values_retry'];
            unset($value['max_unique_values_retry']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['locale'])) {
            $output['locale'] = $this->locale;
        }
        if (isset($this->_usedProperties['seed'])) {
            $output['seed'] = $this->seed;
        }
        if (isset($this->_usedProperties['functionsBlacklist'])) {
            $output['functions_blacklist'] = $this->functionsBlacklist;
        }
        if (isset($this->_usedProperties['loadingLimit'])) {
            $output['loading_limit'] = $this->loadingLimit;
        }
        if (isset($this->_usedProperties['maxUniqueValuesRetry'])) {
            $output['max_unique_values_retry'] = $this->maxUniqueValuesRetry;
        }

        return $output;
    }

}
