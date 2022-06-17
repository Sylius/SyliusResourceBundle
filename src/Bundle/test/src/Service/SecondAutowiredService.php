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

namespace App\Service;

use App\Factory\BookFactoryInterface;
use App\Repository\BookRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class SecondAutowiredService
{
    public BookFactoryInterface $bookFactory;

    public BookRepositoryInterface $bookRepository;

    public EntityManagerInterface $bookManager;

    public function __construct(
        BookFactoryInterface $bookFactory,
        BookRepositoryInterface $bookRepository,
        EntityManagerInterface $bookManager,
    ) {
        $this->bookFactory = $bookFactory;
        $this->bookRepository = $bookRepository;
        $this->bookManager = $bookManager;
    }
}
