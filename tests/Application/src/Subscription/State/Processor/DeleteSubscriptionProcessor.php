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

namespace App\Subscription\State\Processor;

use App\Subscription\Entity\Subscription;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Doctrine\Common\State\RemoveProcessor;
use Sylius\Resource\Exception\WriteResourceException;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Webmozart\Assert\Assert;

final class DeleteSubscriptionProcessor implements ProcessorInterface
{
    public function __construct(
        private RemoveProcessor $removeProcessor,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        Assert::isInstanceOf($data, Subscription::class);

        if ('accepted' === $data->state) {
            throw new WriteResourceException(message: 'app.subscription.delete_error_accepted');
        }

        return $this->removeProcessor->process($data, $operation, $context);
    }
}
