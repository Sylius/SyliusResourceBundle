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
use Sylius\Component\Resource\Annotation\SyliusRoute;

#[SyliusRoute(
    name: 'update_book_with_redirect_options',
    path: '/genre/{genreId}/books/{id}',
    methods: ['GET', 'PUT'],
    controller: 'app.controller.book::updateAction',
    redirect: [
        'route' => 'app_genre_show',
        'parameters' => ['id' => '$genreId'],
    ],
)]
final class UpdateBookWithRedirectOptions extends Book
{
}
