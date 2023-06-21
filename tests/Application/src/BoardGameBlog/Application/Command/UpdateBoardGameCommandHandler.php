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

use App\BoardGameBlog\Domain\Exception\MissingBoardGameException;
use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\Repository\BoardGameRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

final class UpdateBoardGameCommandHandler implements CommandHandlerInterface
{
    public function __construct(private BoardGameRepositoryInterface $boardGameRepository)
    {
    }

    public function __invoke(UpdateBoardGameCommand $command): BoardGame
    {
        $boardGame = $this->boardGameRepository->ofId($command->id);
        if (null === $boardGame) {
            throw new MissingBoardGameException($command->id);
        }

        $boardGame->update(
            name: $command->name,
        );

        $this->boardGameRepository->save($boardGame);

        return $boardGame;
    }
}
