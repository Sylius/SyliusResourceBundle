<?php

namespace ContainerBoRyxcn;
include_once \dirname(__DIR__, 6).'/vendor/sylius/grid-bundle/src/Bundle/Templating/Helper/GridHelper.php';

class GridHelperGhostF533c61 extends \Sylius\Bundle\GridBundle\Templating\Helper\GridHelper implements \Symfony\Component\VarExporter\LazyObjectInterface
{
    use \Symfony\Component\VarExporter\LazyGhostTrait;

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'gridRenderer' => [parent::class, 'gridRenderer', null],
        'gridRenderer' => [parent::class, 'gridRenderer', null],
    ];
}

// Help opcache.preload discover always-needed symbols
class_exists(\Symfony\Component\VarExporter\Internal\Hydrator::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectRegistry::class);
class_exists(\Symfony\Component\VarExporter\Internal\LazyObjectState::class);

if (!\class_exists('GridHelperGhostF533c61', false)) {
    \class_alias(__NAMESPACE__.'\\GridHelperGhostF533c61', 'GridHelperGhostF533c61', false);
}
