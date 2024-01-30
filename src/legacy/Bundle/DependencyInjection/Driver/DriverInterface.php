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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver;

use Sylius\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface DriverInterface
{
    public function load(ContainerBuilder $container, MetadataInterface $metadata): void;

    /**
     * Returns unique name of the driver.
     */
    public function getType(): string;
}
