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
use JMS\Serializer\Annotation as Serializer;
use RectorPrefix20210817\template;
use Sylius\Component\Resource\Annotation\SyliusRoute;

/**
 * @Serializer\ExclusionPolicy("all")
 */
#[SyliusRoute(
    name: 'show_book_with_serialization_version',
    path: '/book/{id}',
    controller: 'app.controller.book:showAction',
    serializationVersion: '1.0'
)]
class ShowBookWithSerializationVersion extends Book
{
}
