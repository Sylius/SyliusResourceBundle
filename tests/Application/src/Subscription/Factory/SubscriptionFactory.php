<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Subscription\Factory;

use App\Subscription\Entity\Subscription;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class SubscriptionFactory implements FactoryInterface
{
    public function createNew(): Subscription
    {
        return new Subscription(email: 'new@example.com');
    }
}
