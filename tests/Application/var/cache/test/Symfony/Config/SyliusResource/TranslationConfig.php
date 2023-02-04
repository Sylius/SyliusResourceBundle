<?php

namespace Symfony\Config\SyliusResource;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class TranslationConfig 
{
    private $enabled;
    private $localeProvider;
    private $_usedProperties = [];

    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enabled($value): static
    {
        $this->_usedProperties['enabled'] = true;
        $this->enabled = $value;

        return $this;
    }

    /**
     * @default 'sylius.translation_locale_provider.immutable'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function localeProvider($value): static
    {
        $this->_usedProperties['localeProvider'] = true;
        $this->localeProvider = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('locale_provider', $value)) {
            $this->_usedProperties['localeProvider'] = true;
            $this->localeProvider = $value['locale_provider'];
            unset($value['locale_provider']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['enabled'])) {
            $output['enabled'] = $this->enabled;
        }
        if (isset($this->_usedProperties['localeProvider'])) {
            $output['locale_provider'] = $this->localeProvider;
        }

        return $output;
    }

}
