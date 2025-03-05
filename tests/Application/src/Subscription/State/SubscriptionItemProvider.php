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

namespace App\Subscription\State;

use App\Subscription\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Console\Context\ConsoleOption;
use Webmozart\Assert\Assert;

final class SubscriptionItemProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function provide(Operation $operation, Context $context): ?Subscription
    {
        $id = $context->get(ConsoleOption::class)?->input()->getOption('id');
        Assert::notNull($id);

        return $this->entityManager->getRepository(Subscription::class)->find($id);
    }
}
