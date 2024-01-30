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

namespace Sylius\Resource\Model;

interface TimestampableInterface
{
    public function getCreatedAt(): ?\DateTimeInterface;

    /** @psalm-suppress MissingReturnType */
    public function setCreatedAt(?\DateTimeInterface $createdAt);

    public function getUpdatedAt(): ?\DateTimeInterface;

    /** @psalm-suppress MissingReturnType */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt);
}

class_alias(TimestampableInterface::class, \Sylius\Component\Resource\Model\TimestampableInterface::class);
