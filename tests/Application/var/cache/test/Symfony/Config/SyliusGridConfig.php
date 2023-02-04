<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'SyliusGrid'.\DIRECTORY_SEPARATOR.'TemplatesConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'SyliusGrid'.\DIRECTORY_SEPARATOR.'GridsConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class SyliusGridConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $drivers;
    private $templates;
    private $grids;
    private $_usedProperties = [];

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function drivers(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['drivers'] = true;
        $this->drivers = $value;

        return $this;
    }

    /**
     * @default {"filter":[],"action":[],"bulk_action":[]}
    */
    public function templates(array $value = []): \Symfony\Config\SyliusGrid\TemplatesConfig
    {
        if (null === $this->templates) {
            $this->_usedProperties['templates'] = true;
            $this->templates = new \Symfony\Config\SyliusGrid\TemplatesConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "templates()" has already been initialized. You cannot pass values the second time you call templates().');
        }

        return $this->templates;
    }

    public function grids(string $code, array $value = []): \Symfony\Config\SyliusGrid\GridsConfig
    {
        if (!isset($this->grids[$code])) {
            $this->_usedProperties['grids'] = true;
            $this->grids[$code] = new \Symfony\Config\SyliusGrid\GridsConfig($value);
        } elseif (1 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "grids()" has already been initialized. You cannot pass values the second time you call grids().');
        }

        return $this->grids[$code];
    }

    public function getExtensionAlias(): string
    {
        return 'sylius_grid';
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('drivers', $value)) {
            $this->_usedProperties['drivers'] = true;
            $this->drivers = $value['drivers'];
            unset($value['drivers']);
        }

        if (array_key_exists('templates', $value)) {
            $this->_usedProperties['templates'] = true;
            $this->templates = new \Symfony\Config\SyliusGrid\TemplatesConfig($value['templates']);
            unset($value['templates']);
        }

        if (array_key_exists('grids', $value)) {
            $this->_usedProperties['grids'] = true;
            $this->grids = array_map(function ($v) { return new \Symfony\Config\SyliusGrid\GridsConfig($v); }, $value['grids']);
            unset($value['grids']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['drivers'])) {
            $output['drivers'] = $this->drivers;
        }
        if (isset($this->_usedProperties['templates'])) {
            $output['templates'] = $this->templates->toArray();
        }
        if (isset($this->_usedProperties['grids'])) {
            $output['grids'] = array_map(function ($v) { return $v->toArray(); }, $this->grids);
        }

        return $output;
    }

}
