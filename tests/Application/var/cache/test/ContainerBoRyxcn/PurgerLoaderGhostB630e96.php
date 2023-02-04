<?php

namespace ContainerBoRyxcn;
include_once \dirname(__DIR__, 6).'/vendor/theofidry/alice-data-fixtures/src/LoaderInterface.php';
include_once \dirname(__DIR__, 6).'/vendor/theofidry/alice-data-fixtures/src/Persistence/PersisterAwareInterface.php';
include_once \dirname(__DIR__, 6).'/vendor/nelmio/alice/src/IsAServiceTrait.php';
include_once \dirname(__DIR__, 6).'/vendor/theofidry/alice-data-fixtures/src/Loader/PurgerLoader.php';

class PurgerLoaderGhostB630e96 extends \Fidry\AliceDataFixtures\Loader\PurgerLoader implements \Symfony\Component\VarExporter\LazyObjectInterface
{
    use \Symfony\Component\VarExporter\LazyGhostTrait;

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'defaultPurgeMode' => [parent::class, 'defaultPurgeMode', null],
        "\0".parent::class."\0".'loader' => [parent::class, 'loader', null],
        "\0".parent::class."\0".'logger' => [parent::class, 'logger', null],
        "\0".parent::class."\0".'purgerFactory' => [parent::class, 'purgerFactory', null],
        'defaultPurgeMode' => [parent::class, 'defaultPurgeMode', null],
        'loader' => [parent::class, 'loader', null],
        'logger' => [parent::class, 'logger', null],
        'purgerFactory' => [parent::class, 'purgerFactory', null],
    ];
}

// Help opcache.preload discover always-needed symbols
class_exists(\Symfony\Component\VarExporter\Internal\Hydrator::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectRegistry::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectState::class);

if (!\class_exists('PurgerLoaderGhostB630e96', false)) {
    \class_alias(__NAMESPACE__.'\\PurgerLoaderGhostB630e96', 'PurgerLoaderGhostB630e96', false);
}
