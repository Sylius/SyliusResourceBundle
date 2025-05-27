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

use App\Entity\BlogPost;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<BlogPost>
 */
final class BlogPostFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return BlogPost::class;
    }

    public function onDraft(): self
    {
        return $this->with(['currentPlace' => ['draft' => 1]]);
    }

    public function reviewed(): self
    {
        return $this->with(['currentPlace' => ['reviewed' => 1]]);
    }

    protected function defaults(): array
    {
        return [];
    }
}
