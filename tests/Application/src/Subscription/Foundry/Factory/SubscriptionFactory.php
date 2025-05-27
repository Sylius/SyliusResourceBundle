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

namespace App\Subscription\Foundry\Factory;

use App\Subscription\Entity\Subscription;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Subscription>
 */
final class SubscriptionFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Subscription::class;
    }

    public function withEmail(string $email): self
    {
        return $this->with(['email' => $email]);
    }

    public function accepted(): self
    {
        return $this->with(['state' => 'accepted']);
    }

    protected function defaults(): array
    {
        return [
            'email' => self::faker()->email(),
        ];
    }
}
