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

namespace App\Repository;

use App\Entity\Book;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CustomBookRepository
{
    private RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findCustomBook(string $author): ?Book
    {
        return $this->repository->findOneBy(['author' => $author]);
    }

    public function findCustomBooks(): iterable
    {
        return $this->repository->createPaginator();
    }
}
