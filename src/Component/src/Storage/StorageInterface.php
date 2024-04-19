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

namespace Sylius\Resource\Storage;

use Sylius\Resource\Exception\StorageUnavailableException;

interface StorageInterface
{
    /**
     * @throws StorageUnavailableException
     */
    public function has(string $name): bool;

    /**
     * @param mixed $default
     *
     * @return mixed
     *
     * @throws StorageUnavailableException
     */
    public function get(string $name, $default = null);

    /**
     * @param mixed $value
     *
     * @throws StorageUnavailableException
     */
    public function set(string $name, $value): void;

    /**
     * @throws StorageUnavailableException
     */
    public function remove(string $name): void;

    /**
     * @throws StorageUnavailableException
     */
    public function all(): array;
}

class_alias(StorageInterface::class, \Sylius\Component\Resource\Storage\StorageInterface::class);
