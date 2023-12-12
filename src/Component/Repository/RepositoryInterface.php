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

namespace Sylius\Component\Resource\Repository;

interface_exists(\Sylius\Resource\Doctrine\Persistence\RepositoryInterface::class);

if (false) {
    /**
     * @inheritDoc
     */
    interface RepositoryInterface extends \Sylius\Resource\Doctrine\Persistence\RepositoryInterface
    {
    }
}
