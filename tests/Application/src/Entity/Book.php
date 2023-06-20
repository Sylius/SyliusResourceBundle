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
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * @Serializer\ExclusionPolicy("all")
 */
#[ORM\Entity]
#[ORM\MappedSuperclass]
#[ORM\Table(name: 'app_book')]
class Book implements ResourceInterface, TranslatableInterface
{
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

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
    private int $id;

    /**
     * @Serializer\Expose
     *
     * @Serializer\Type("string")
     */
    #[ORM\Column(length: 255)]
    private ?string $author = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     *
     * @Serializer\VirtualProperty()
     *
     * @Serializer\SerializedName("title")
     */
    public function getTitle()
    {
        return $this->getTranslation()->getTitle();
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->getTranslation()->setTitle($title);
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    protected function createTranslation(): BookTranslation
    {
        return new BookTranslation();
    }
}
