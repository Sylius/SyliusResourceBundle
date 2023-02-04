<?php

namespace ContainerBoRyxcn;
include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Bundle/Templating/Helper/BulkActionGridHelper.php';

class BulkActionGridHelperGhost3971b73 extends \Sylius\Bundle\GridBundle\Templating\Helper\BulkActionGridHelper implements \Symfony\Component\VarExporter\LazyObjectInterface
{
    use \Symfony\Component\VarExporter\LazyGhostTrait;

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'bulkActionGridRenderer' => [parent::class, 'bulkActionGridRenderer', null],
        'bulkActionGridRenderer' => [parent::class, 'bulkActionGridRenderer', null],
    ];
}

// Help opcache.preload discover always-needed symbols
class_exists(\Symfony\Component\VarExporter\Internal\Hydrator::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectRegistry::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectState::class);

if (!\class_exists('BulkActionGridHelperGhost3971b73', false)) {
    \class_alias(__NAMESPACE__.'\\BulkActionGridHelperGhost3971b73', 'BulkActionGridHelperGhost3971b73', false);
}
