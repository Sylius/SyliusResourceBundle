<?php

namespace Symfony\Config\Framework;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class AssetMapperConfig 
{
    private $enabled;
    private $paths;
    private $excludedPatterns;
    private $server;
    private $publicPrefix;
    private $missingImportMode;
    private $extensions;
    private $importmapPath;
    private $importmapPolyfill;
    private $importmapScriptAttributes;
    private $vendorDir;
    private $provider;
    private $_usedProperties = [];

    /**
     * @default false
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
     * @return $this
     */
    public function path(string $namespace, mixed $value): static
    {
        $this->_usedProperties['paths'] = true;
        $this->paths[$namespace] = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function excludedPatterns(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['excludedPatterns'] = true;
        $this->excludedPatterns = $value;

        return $this;
    }

    /**
     * If true, a "dev server" will return the assets from the public directory (true in "debug" mode only by default)
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function server($value): static
    {
        $this->_usedProperties['server'] = true;
        $this->server = $value;

        return $this;
    }

    /**
     * The public path where the assets will be written to (and served from when "server" is true)
     * @default '/assets/'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function publicPrefix($value): static
    {
        $this->_usedProperties['publicPrefix'] = true;
        $this->publicPrefix = $value;

        return $this;
    }

    /**
     * Behavior if an asset cannot be found when imported from JavaScript or CSS files - e.g. "import './non-existent.js'". "strict" means an exception is thrown, "warn" means a warning is logged, "ignore" means the import is left as-is.
     * @default 'warn'
     * @param ParamConfigurator|'strict'|'warn'|'ignore' $value
     * @return $this
     */
    public function missingImportMode($value): static
    {
        $this->_usedProperties['missingImportMode'] = true;
        $this->missingImportMode = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function extension(string $extension, mixed $value): static
    {
        $this->_usedProperties['extensions'] = true;
        $this->extensions[$extension] = $value;

        return $this;
    }

    /**
     * The path of the importmap.php file.
     * @default '%kernel.project_dir%/importmap.php'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function importmapPath($value): static
    {
        $this->_usedProperties['importmapPath'] = true;
        $this->importmapPath = $value;

        return $this;
    }

    /**
     * URL of the ES Module Polyfill to use, false to disable. Defaults to using a CDN URL.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function importmapPolyfill($value): static
    {
        $this->_usedProperties['importmapPolyfill'] = true;
        $this->importmapPolyfill = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function importmapScriptAttribute(string $key, mixed $value): static
    {
        $this->_usedProperties['importmapScriptAttributes'] = true;
        $this->importmapScriptAttributes[$key] = $value;

        return $this;
    }

    /**
     * The directory to store JavaScript vendors.
     * @default '%kernel.project_dir%/assets/vendor'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function vendorDir($value): static
    {
        $this->_usedProperties['vendorDir'] = true;
        $this->vendorDir = $value;

        return $this;
    }

    /**
     * The provider (CDN) to use.
     * @default 'jsdelivr.esm'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function provider($value): static
    {
        $this->_usedProperties['provider'] = true;
        $this->provider = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('paths', $value)) {
            $this->_usedProperties['paths'] = true;
            $this->paths = $value['paths'];
            unset($value['paths']);
        }

        if (array_key_exists('excluded_patterns', $value)) {
            $this->_usedProperties['excludedPatterns'] = true;
            $this->excludedPatterns = $value['excluded_patterns'];
            unset($value['excluded_patterns']);
        }

        if (array_key_exists('server', $value)) {
            $this->_usedProperties['server'] = true;
            $this->server = $value['server'];
            unset($value['server']);
        }

        if (array_key_exists('public_prefix', $value)) {
            $this->_usedProperties['publicPrefix'] = true;
            $this->publicPrefix = $value['public_prefix'];
            unset($value['public_prefix']);
        }

        if (array_key_exists('missing_import_mode', $value)) {
            $this->_usedProperties['missingImportMode'] = true;
            $this->missingImportMode = $value['missing_import_mode'];
            unset($value['missing_import_mode']);
        }

        if (array_key_exists('extensions', $value)) {
            $this->_usedProperties['extensions'] = true;
            $this->extensions = $value['extensions'];
            unset($value['extensions']);
        }

        if (array_key_exists('importmap_path', $value)) {
            $this->_usedProperties['importmapPath'] = true;
            $this->importmapPath = $value['importmap_path'];
            unset($value['importmap_path']);
        }

        if (array_key_exists('importmap_polyfill', $value)) {
            $this->_usedProperties['importmapPolyfill'] = true;
            $this->importmapPolyfill = $value['importmap_polyfill'];
            unset($value['importmap_polyfill']);
        }

        if (array_key_exists('importmap_script_attributes', $value)) {
            $this->_usedProperties['importmapScriptAttributes'] = true;
            $this->importmapScriptAttributes = $value['importmap_script_attributes'];
            unset($value['importmap_script_attributes']);
        }

        if (array_key_exists('vendor_dir', $value)) {
            $this->_usedProperties['vendorDir'] = true;
            $this->vendorDir = $value['vendor_dir'];
            unset($value['vendor_dir']);
        }

        if (array_key_exists('provider', $value)) {
            $this->_usedProperties['provider'] = true;
            $this->provider = $value['provider'];
            unset($value['provider']);
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
        if (isset($this->_usedProperties['paths'])) {
            $output['paths'] = $this->paths;
        }
        if (isset($this->_usedProperties['excludedPatterns'])) {
            $output['excluded_patterns'] = $this->excludedPatterns;
        }
        if (isset($this->_usedProperties['server'])) {
            $output['server'] = $this->server;
        }
        if (isset($this->_usedProperties['publicPrefix'])) {
            $output['public_prefix'] = $this->publicPrefix;
        }
        if (isset($this->_usedProperties['missingImportMode'])) {
            $output['missing_import_mode'] = $this->missingImportMode;
        }
        if (isset($this->_usedProperties['extensions'])) {
            $output['extensions'] = $this->extensions;
        }
        if (isset($this->_usedProperties['importmapPath'])) {
            $output['importmap_path'] = $this->importmapPath;
        }
        if (isset($this->_usedProperties['importmapPolyfill'])) {
            $output['importmap_polyfill'] = $this->importmapPolyfill;
        }
        if (isset($this->_usedProperties['importmapScriptAttributes'])) {
            $output['importmap_script_attributes'] = $this->importmapScriptAttributes;
        }
        if (isset($this->_usedProperties['vendorDir'])) {
            $output['vendor_dir'] = $this->vendorDir;
        }
        if (isset($this->_usedProperties['provider'])) {
            $output['provider'] = $this->provider;
        }

        return $output;
    }

}
