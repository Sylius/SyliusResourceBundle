<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Symfony\Routing\Factory;

use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Symfony\Component\Routing\Route;

interface OperationRouteFactoryInterface
{
    public function create(MetadataInterface $metadata, ResourceMetadata $resource, HttpOperation $operation): Route;
}
