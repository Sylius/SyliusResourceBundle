<?php

declare(strict_types=1);

namespace Sylius\Bundle\ResourceBundle\Routing;

use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Symfony\Component\Routing\Route;

interface OperationRouteFactoryInterface
{
    public function create(MetadataInterface $metadata, Operation $operation): Route;
}
