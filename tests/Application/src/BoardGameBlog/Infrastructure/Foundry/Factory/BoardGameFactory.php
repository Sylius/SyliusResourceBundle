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

namespace App\BoardGameBlog\Infrastructure\Foundry\Factory;

use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\ValueObject\BoardGameName;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<BoardGame>
 */
final class BoardGameFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return BoardGame::class;
    }

    public function withName(BoardGameName $name): self
    {
        return $this->with(['name' => $name]);
    }

    protected function defaults(): array
    {
        return [
            'name' => new BoardGameName(ucfirst(self::faker()->words(2, true))),
        ];
    }

    protected function initialize(): static
    {
        return parent::instantiateWith(function (array $attributes): BoardGame {
            return new BoardGame(...$attributes);
        });
    }
}
