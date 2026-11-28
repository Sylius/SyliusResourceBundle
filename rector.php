<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return RectorConfig::configure()
    ->withImportNames(importShortClasses: false)
    ->withRules([
        TypedPropertyFromAssignsRector::class,
    ])
;
