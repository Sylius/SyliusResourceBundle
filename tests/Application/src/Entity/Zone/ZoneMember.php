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

namespace App\Entity\Zone;

use Doctrine\ORM\Mapping as ORM;

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
