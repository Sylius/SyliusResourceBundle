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

namespace App\Calendar\Entity;

use App\Calendar\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[AsResource(
    section: 'admin',
    routePrefix: '/admin',
    templatesDir: 'calendar/event',
)]
#[Index]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event implements ResourceInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer', unique: true)]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        public ?int $id = null,
        #[Assert\NotBlank]
        #[ORM\Column(name: 'name', nullable: false)]
        public ?string $name = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
