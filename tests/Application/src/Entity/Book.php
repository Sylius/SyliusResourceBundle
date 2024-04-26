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

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TranslatableTrait;

#[ORM\Entity]
#[ORM\MappedSuperclass]
#[ORM\Table(name: 'app_book')]
#[Serializer\ExclusionPolicy('all')]
class Book implements ResourceInterface, TranslatableInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Expose]
    #[Serializer\Type('integer')]
    #[Serializer\XmlAttribute]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Serializer\Expose]
    #[Serializer\Type('string')]
    private ?string $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Serializer\VirtualProperty]
    #[Serializer\SerializedName('title')]
    public function getTitle(): ?string
    {
        return $this->getTranslation()->getTitle();
    }

    public function setTitle(?string $title): void
    {
        $this->getTranslation()->setTitle($title);
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    protected function createTranslation(): BookTranslation
    {
        return new BookTranslation();
    }
}
