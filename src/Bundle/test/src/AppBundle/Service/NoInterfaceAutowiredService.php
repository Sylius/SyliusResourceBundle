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

namespace AppBundle\Service;

use AppBundle\Factory\BookFactory;
use AppBundle\Repository\BookRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;

final class NoInterfaceAutowiredService
{
    /** @var BookFactory */
    private $bookFactory;

    /** @var BookRepository */
    private $bookRepository;

    /** @var Factory */
    private $comicBookFactory;

    /** @var EntityRepository */
    private $comicBookRepository;

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
