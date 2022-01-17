<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class ScienceBook implements ResourceInterface
{
    private ?int $id;

    private ?Author $author = null;

    private ?string $title = null;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getAuthorFirstName(): ?string
    {
        return $this->author ? $this->author->getFirstName() : null;
    }

    public function getAuthorLastName(): ?string
    {
        return $this->author ? $this->author->getLastName() : null;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): void
    {
        $this->author = $author;
    }
}
