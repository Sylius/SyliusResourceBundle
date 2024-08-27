<?php

namespace App\Entity\Zone;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Model\ResourceInterface;

#[ORM\Entity]
class ZoneMember implements ZoneMemberInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $code = null;

    #[ORM\ManyToOne(targetEntity: ZoneInterface::class, inversedBy: 'members')]
    public ZoneInterface $belongsTo;

    public function getId(): ?int
    {
        return $this->id;
    }
}
