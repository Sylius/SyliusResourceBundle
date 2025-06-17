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

namespace App\Conference\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity]
class Speaker implements ResourceInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer', unique: true)]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        public ?int $id = null,
        #[NotBlank]
        #[ORM\Column]
        public ?string $firstName = null,
        #[NotBlank]
        #[ORM\Column]
        public ?string $lastName = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
