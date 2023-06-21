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

namespace App\BoardGameBlog\Domain\Repository;

use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\ValueObject\BoardGameId;
use App\Shared\Domain\Repository\RepositoryInterface;

/**
 * @extends RepositoryInterface<BoardGame>
 */
interface BoardGameRepositoryInterface extends RepositoryInterface
{
    public function save(BoardGame $boardGame): void;

    public function remove(BoardGame $boardGame): void;

    public function ofId(BoardGameId $id): ?BoardGame;
}
