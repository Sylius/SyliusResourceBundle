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

namespace App\Entity\Route;

use App\Entity\Book;
use Sylius\Component\Resource\Annotation\SyliusRoute;

#[SyliusRoute(
    name: 'show_book_with_permission',
    path: '/books/{id}',
    methods: ['GET', 'POST'],
    controller: 'app.controller.book::showAction',
    permission: true,
)]
final class ShowBookWithPermission extends Book
{
}
