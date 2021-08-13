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

use App\Factory\BookFactory;
use App\Repository\BookRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;

final class NoInterfaceAutowiredService
{
    private BookFactory $bookFactory;

    private BookRepository $bookRepository;

    private Factory $comicBookFactory;

    private EntityRepository $comicBookRepository;

    public function __construct(
        BookFactory $bookFactory,
        BookRepository $bookRepository,
        Factory $comicBookFactory,
        EntityRepository $comicBookRepository
    ) {
        $this->bookFactory = $bookFactory;
        $this->bookRepository = $bookRepository;
        $this->comicBookFactory = $comicBookFactory;
        $this->comicBookRepository = $comicBookRepository;
    }
}
