<?php

namespace Symfony\Config\Twig;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class MailerConfig 
{
    private $htmlToTextConverter;
    private $_usedProperties = [];

    /**
     * A service implementing the "Symfony\Component\Mime\HtmlToTextConverter\HtmlToTextConverterInterface"
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function htmlToTextConverter($value): static
    {
        $this->_usedProperties['htmlToTextConverter'] = true;
        $this->htmlToTextConverter = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('html_to_text_converter', $value)) {
            $this->_usedProperties['htmlToTextConverter'] = true;
            $this->htmlToTextConverter = $value['html_to_text_converter'];
            unset($value['html_to_text_converter']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['htmlToTextConverter'])) {
            $output['html_to_text_converter'] = $this->htmlToTextConverter;
        }

        return $output;
    }

}
