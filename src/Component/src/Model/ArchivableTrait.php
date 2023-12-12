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

/**
 * @see ArchivableInterface
 */
trait ArchivableTrait
{
    /** @var \DateTimeInterface|null */
    protected $archivedAt;

    public function getArchivedAt(): ?\DateTimeInterface
    {
        return $this->archivedAt;
    }

    public function setArchivedAt(?\DateTimeInterface $archivedAt): void
    {
        $this->archivedAt = $archivedAt;
    }
}

class_alias(ArchivableTrait::class, \Sylius\Component\Resource\Model\ArchivableTrait::class);
