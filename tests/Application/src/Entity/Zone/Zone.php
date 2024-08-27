<?php

namespace App\Entity\Zone;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Country;

#[ORM\Entity]
class Zone implements ZoneInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    public ?string $code = null;

    #[ORM\Column(length: 255)]
    public ?string $name = null;

    #[Orm\OneToMany(mappedBy: 'belongsTo', targetEntity: ZoneMemberInterface::class, orphanRemoval: true)]
    public Collection $members;

    public function getId(): ?int
    {
        return $this->id;
    }
}
