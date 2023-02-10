<?php

declare(strict_types=1);

namespace App\Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Resource(
    alias: 'app.subscription',
    section: 'admin',
    templatesDir: 'crud',
    routePrefix: '/admin',
)]
#[Index(grid: 'app_subscription')]
#[Create]
#[Update]
#[Delete]
#[Show(template: 'subscription/show.html.twig')]
#[ORM\Entity]
class Subscription implements ResourceInterface
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        #[ORM\GeneratedValue(strategy: 'CUSTOM')]
        #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
        public ?Uuid $id = null,

        #[Assert\NotBlank]
        #[Assert\Email]
        #[ORM\Column(name: 'name', nullable: false)]
        public ?string $email = null,
    ) {
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
