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

interface ArchivableInterface
{
    public function getArchivedAt(): ?\DateTimeInterface;

    public function setArchivedAt(?\DateTimeInterface $archivedAt): void;
}

class_alias(ArchivableInterface::class, \Sylius\Component\Resource\Model\ArchivableInterface::class);
