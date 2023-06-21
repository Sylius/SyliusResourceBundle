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

namespace App\BoardGameBlog\Application\Command;

use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\Repository\BoardGameRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

final class CreateBoardGameCommandHandler implements CommandHandlerInterface
{
    public function __construct(private BoardGameRepositoryInterface $boardGameRepository)
    {
    }

    public function __invoke(CreateBoardGameCommand $command): BoardGame
    {
        $boardGame = new BoardGame(
            $command->name,
        );

        $this->boardGameRepository->save($boardGame);

        return $boardGame;
    }
}
