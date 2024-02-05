<?php

namespace App\Service;

use Sylius\Component\Resource\Factory\FactoryInterface;

final class LegacyAutowiredFactoryService
{
    public function __construct(
        private FactoryInterface $legacyBookFactory,
    ) {
    }
}
