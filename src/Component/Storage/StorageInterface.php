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

namespace Sylius\Component\Resource\Storage;

interface StorageInterface
{
    public function has(string $name): bool;

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param mixed $value
     */
    public function set(string $name, $value): void;

    public function remove(string $name): void;

    public function all(): array;
}
