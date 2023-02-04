<?php

namespace Symfony\Config;

require_once __DIR__.\DIRECTORY_SEPARATOR.'FidryAliceDataFixtures'.\DIRECTORY_SEPARATOR.'DbDriversConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class FidryAliceDataFixturesConfig implements \Symfony\Component\Config\Builder\ConfigBuilderInterface
{
    private $defaultPurgeMode;
    private $dbDrivers;
    private $_usedProperties = [];

    /**
     * @default 'delete'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultPurgeMode($value): static
    {
        $this->_usedProperties['defaultPurgeMode'] = true;
        $this->defaultPurgeMode = $value;

        return $this;
    }

    /**
     * The list of enabled drivers.
     * @default {"doctrine_orm":null,"doctrine_mongodb_odm":null,"doctrine_phpcr_odm":null,"eloquent_orm":null}
    */
    public function dbDrivers(array $value = []): \Symfony\Config\FidryAliceDataFixtures\DbDriversConfig
    {
        if (null === $this->dbDrivers) {
            $this->_usedProperties['dbDrivers'] = true;
            $this->dbDrivers = new \Symfony\Config\FidryAliceDataFixtures\DbDriversConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "dbDrivers()" has already been initialized. You cannot pass values the second time you call dbDrivers().');
        }

        return $this->dbDrivers;
    }

    public function getExtensionAlias(): string
    {
        return 'fidry_alice_data_fixtures';
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('default_purge_mode', $value)) {
            $this->_usedProperties['defaultPurgeMode'] = true;
            $this->defaultPurgeMode = $value['default_purge_mode'];
            unset($value['default_purge_mode']);
        }

        if (array_key_exists('db_drivers', $value)) {
            $this->_usedProperties['dbDrivers'] = true;
            $this->dbDrivers = new \Symfony\Config\FidryAliceDataFixtures\DbDriversConfig($value['db_drivers']);
            unset($value['db_drivers']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['defaultPurgeMode'])) {
            $output['default_purge_mode'] = $this->defaultPurgeMode;
        }
        if (isset($this->_usedProperties['dbDrivers'])) {
            $output['db_drivers'] = $this->dbDrivers->toArray();
        }

        return $output;
    }

}
