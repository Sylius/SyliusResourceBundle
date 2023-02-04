<?php

namespace ContainerBoRyxcn;
include_once \dirname(__DIR__, 6).'/vendor/theofidry/alice-data-fixtures/src/LoaderInterface.php';
include_once \dirname(__DIR__, 6).'/vendor/theofidry/alice-data-fixtures/src/Persistence/PersisterAwareInterface.php';
include_once \dirname(__DIR__, 6).'/vendor/nelmio/alice/src/IsAServiceTrait.php';
include_once \dirname(__DIR__, 6).'/vendor/theofidry/alice-data-fixtures/src/Loader/PersisterLoader.php';

class PersisterLoaderGhostC463573 extends \Fidry\AliceDataFixtures\Loader\PersisterLoader implements \Symfony\Component\VarExporter\LazyObjectInterface
{
    use \Symfony\Component\VarExporter\LazyGhostTrait;

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'loader' => [parent::class, 'loader', null],
        "\0".parent::class."\0".'logger' => [parent::class, 'logger', null],
        "\0".parent::class."\0".'persister' => [parent::class, 'persister', null],
        "\0".parent::class."\0".'processors' => [parent::class, 'processors', null],
        'loader' => [parent::class, 'loader', null],
        'logger' => [parent::class, 'logger', null],
        'persister' => [parent::class, 'persister', null],
        'processors' => [parent::class, 'processors', null],
    ];
}

// Help opcache.preload discover always-needed symbols
class_exists(\Symfony\Component\VarExporter\Internal\Hydrator::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectRegistry::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectState::class);

if (!\class_exists('PersisterLoaderGhostC463573', false)) {
    \class_alias(__NAMESPACE__.'\\PersisterLoaderGhostC463573', 'PersisterLoaderGhostC463573', false);
}
