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

namespace App\Entity\Route;

use App\Entity\Book;
use JMS\Serializer\Annotation as Serializer;
use Sylius\Resource\Annotation\SyliusRoute;

#[SyliusRoute(
    name: 'show_book_with_repository',
    path: '/book/{id}',
    controller: 'app.controller.book::showAction',
    repository: [
        'method' => 'findOneNewestByAuthor',
        'arguments' => '[$author]',
    ],
)]
#[Serializer\ExclusionPolicy('all')]
class ShowBookWithRepository extends Book
{
}
