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

namespace App\Subscription\Entity;

use App\Subscription\Form\Type\SubscriptionType;
use App\Subscription\Twig\Context\Factory\ShowSubscriptionContextFactory;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\Api;
use Sylius\Resource\Metadata\ApplyStateMachineTransition;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\BulkUpdate;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[AsResource(
    section: 'admin',
    formType: SubscriptionType::class,
    templatesDir: 'crud',
    routePrefix: '/admin',
)]
#[Index(grid: 'app_subscription')]
#[Create]
#[Update]
#[Delete]
#[BulkDelete]
#[ApplyStateMachineTransition(stateMachineTransition: 'accept')]
#[ApplyStateMachineTransition(stateMachineTransition: 'reject')]
#[BulkUpdate(
    shortName: 'bulk_accept',
    stateMachineTransition: 'accept',
)]
#[Show(
    template: 'subscription/show.html.twig',
    twigContextFactory: ShowSubscriptionContextFactory::class,
)]

#[AsResource(
    alias: 'app.subscription',
    section: 'ajax',
    routePrefix: '/ajax',
    normalizationContext: ['groups' => 'subscription:read'],
    denormalizationContext: ['groups' => 'subscription:write'],
)]
#[Api\GetCollection]
#[Api\Post]
#[Api\Put]
#[Api\Delete]
#[Api\Get]

#[ORM\Entity]
class Subscription implements ResourceInterface
{
    #[ORM\Column(type: 'string')]
    #[Groups(['subscription:read'])]
    public string $state = 'new';

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer', unique: true)]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        public ?int $id = null,

        #[Assert\NotBlank]
        #[Assert\Email]
        #[ORM\Column(name: 'name', nullable: false)]
        #[Groups(['subscription:read', 'subscription:write'])]
        public ?string $email = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $currentState): void
    {
        $this->state = $currentState;
    }
}
