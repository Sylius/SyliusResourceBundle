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

interface SlugAwareInterface
{
    public function getSlug(): ?string;

    public function setSlug(?string $slug): void;
}

class_alias(SlugAwareInterface::class, \Sylius\Component\Resource\Model\SlugAwareInterface::class);
