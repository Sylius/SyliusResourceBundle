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
    name: 'publish_book',
    path: '/books/{id}',
    methods: ['PATCH'],
    controller: 'app.controller.book::applyStateMachineTransitionAction',
    stateMachine: ['graph' => 'app_book', 'transition' => 'publish'],
)]
#[Serializer\ExclusionPolicy('all')]
class PublishBook extends Book
{
}
