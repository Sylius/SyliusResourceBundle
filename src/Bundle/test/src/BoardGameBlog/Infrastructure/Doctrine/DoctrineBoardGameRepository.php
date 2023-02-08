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

namespace App\BoardGameBlog\Infrastructure\Doctrine;

use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\Repository\BoardGameRepositoryInterface;
use App\BoardGameBlog\Domain\ValueObject\BoardGameId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineBoardGameRepository extends ServiceEntityRepository implements BoardGameRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoardGame::class);
    }

    public function save(BoardGame $boardGame): void
    {
        $this->getEntityManager()->persist($boardGame);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->refresh($boardGame);
    }

    public function remove(BoardGame $boardGame): void
    {
        $this->getEntityManager()->remove($boardGame);
        $this->getEntityManager()->flush();
    }

    public function ofId(BoardGameId $id): ?BoardGame
    {
        return $this->find($id->value);
    }
}
