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

use App\Repository\ComicBookRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
#[ORM\Entity(repositoryClass: ComicBookRepository::class)]
#[ORM\MappedSuperclass]
#[ORM\Table(name: 'app_comic_book')]
class ComicBook implements ResourceInterface
{
    /**
     * @Serializer\Expose
     *
     * @Serializer\Type("integer")
     *
     * @Serializer\XmlAttribute
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Serializer\Expose
     *
     * @Serializer\Until("1.1")
     */
    #[ORM\Embedded]
    private ?Author $author = null;

    /**
     * @Serializer\Expose
     *
     * @Serializer\Type("string")
     */
    #[ORM\Column(length: 255)]
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

    /**
     * @Serializer\VirtualProperty()
     *
     * @Serializer\Since("1.1")
     */
    public function getAuthorFirstName(): ?string
    {
        return $this->author?->getFirstName();
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @Serializer\Since("1.1")
     */
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
