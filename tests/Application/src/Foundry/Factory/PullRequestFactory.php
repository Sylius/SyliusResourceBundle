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

namespace App\Foundry\Factory;

use App\Entity\PullRequest;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<PullRequest>
 */
final class PullRequestFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return PullRequest::class;
    }

    public function withCurrentPlace(string $currentPlace): self
    {
        return $this->with(['currentPlace' => $currentPlace]);
    }

    protected function defaults(): array
    {
        return [
            'currentPlace' => self::faker()->randomElement([
                'start',
                'coding',
                'test',
                'review',
                'merged',
                'closed',
            ]),
        ];
    }
}
