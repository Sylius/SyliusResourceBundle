<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src'
    ])
    ->withSkipPath('src/Bundle/spec')
    ->withSkipPath('src/Component/legacy/spec')
    ->withSkipPath('src/Component/spec')
    ->withPhpSets(php80: true)
;
