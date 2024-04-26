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

namespace App\Entity;

use App\Repository\ComicBookRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sylius\Resource\Model\ResourceInterface;

#[ORM\Entity(repositoryClass: ComicBookRepository::class)]
#[ORM\MappedSuperclass]
#[ORM\Table(name: 'app_comic_book')]
#[Serializer\ExclusionPolicy('all')]
class ComicBook implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Expose]
    #[Serializer\Type('integer')]
    #[Serializer\XmlAttribute]
    private ?int $id = null;

    #[ORM\Embedded]
    #[Serializer\Expose]
    #[Serializer\Until('1.1')]
    private ?Author $author = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Expose]
    #[Serializer\Type('string')]
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    #[Serializer\VirtualProperty]
    #[Serializer\Since('1.1')]
    public function getAuthorFirstName(): ?string
    {
        return $this->author?->getFirstName();
    }

    #[Serializer\VirtualProperty]
    #[Serializer\Since('1.1')]
    public function getAuthorLastName(): ?string
    {
        return $this->author?->getLastName();
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
