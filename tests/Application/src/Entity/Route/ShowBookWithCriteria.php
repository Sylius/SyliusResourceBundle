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
    name: 'show_book_with_criteria',
    path: '/library/{libraryId}/book/{id}',
    controller: 'app.controller.book::showAction',
    criteria: ['library' => '$libraryId'],
)]
#[Serializer\ExclusionPolicy('all')]
class ShowBookWithCriteria extends Book
{
}
