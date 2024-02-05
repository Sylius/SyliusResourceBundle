<?php

namespace App\Service;

use Sylius\Component\Resource\Repository\RepositoryInterface;

final class LegacyAutowiredRepositoryService
{
    public function __construct(
        private RepositoryInterface $legacyBookRepository,
    ) {
    }
}
