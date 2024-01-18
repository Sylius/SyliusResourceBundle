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

namespace Sylius\Resource\Doctrine\Persistence\Exception;

class ResourceExistsException extends \RuntimeException implements ExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Given resource already exists in the repository.');
    }
}

class_alias(ResourceExistsException::class, \Sylius\Component\Resource\Repository\Exception\ExistingResourceException::class);
