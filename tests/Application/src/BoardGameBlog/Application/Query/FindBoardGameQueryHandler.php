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

namespace App\BoardGameBlog\Application\Query;

use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\Repository\BoardGameRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final class FindBoardGameQueryHandler implements QueryHandlerInterface
{
    public function __construct(private BoardGameRepositoryInterface $repository)
    {
    }

    public function __invoke(FindBoardGameQuery $query): ?BoardGame
    {
        return $this->repository->ofId($query->id);
    }
}
