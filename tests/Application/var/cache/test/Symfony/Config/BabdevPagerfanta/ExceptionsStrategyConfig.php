<?php

namespace Symfony\Config\BabdevPagerfanta;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ExceptionsStrategyConfig 
{
    private $outOfRangePage;
    private $notValidCurrentPage;
    private $_usedProperties = [];

    /**
     * @default 'to_http_not_found'
     * @param ParamConfigurator|'to_http_not_found'|'custom' $value
     * @return $this
     */
    public function outOfRangePage($value): static
    {
        $this->_usedProperties['outOfRangePage'] = true;
        $this->outOfRangePage = $value;

        return $this;
    }

    /**
     * @default 'to_http_not_found'
     * @param ParamConfigurator|'to_http_not_found'|'custom' $value
     * @return $this
     */
    public function notValidCurrentPage($value): static
    {
        $this->_usedProperties['notValidCurrentPage'] = true;
        $this->notValidCurrentPage = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('out_of_range_page', $value)) {
            $this->_usedProperties['outOfRangePage'] = true;
            $this->outOfRangePage = $value['out_of_range_page'];
            unset($value['out_of_range_page']);
        }

        if (array_key_exists('not_valid_current_page', $value)) {
            $this->_usedProperties['notValidCurrentPage'] = true;
            $this->notValidCurrentPage = $value['not_valid_current_page'];
            unset($value['not_valid_current_page']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['outOfRangePage'])) {
            $output['out_of_range_page'] = $this->outOfRangePage;
        }
        if (isset($this->_usedProperties['notValidCurrentPage'])) {
            $output['not_valid_current_page'] = $this->notValidCurrentPage;
        }

        return $output;
    }

}
