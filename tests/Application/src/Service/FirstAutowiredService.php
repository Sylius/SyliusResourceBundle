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

namespace App\Service;

use Doctrine\Persistence\ObjectManager;
use Sylius\Resource\Doctrine\Peristence\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class FirstAutowiredService
{
    public FactoryInterface $bookFactory;

    public RepositoryInterface $bookRepository;

    public ObjectManager $bookManager;

    public function __construct(
        FactoryInterface $bookFactory,
        RepositoryInterface $bookRepository,
        ObjectManager $bookManager,
    ) {
        $this->bookFactory = $bookFactory;
        $this->bookRepository = $bookRepository;
        $this->bookManager = $bookManager;
    }
}
