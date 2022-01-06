<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Checker;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

interface RequestPermissionCheckerInterface
{
    function isGrantedOr403(RequestConfiguration $configuration, string $permission): void;
}
