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

namespace App\Entity\Operation\Resource;

use App\Entity\Book;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Section;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;

#[Resource(alias: 'app.book')]
#[Section(name: 'admin', routePrefix: 'admin', templatesDir: 'admin/book', operations: [
    new Create(),
    new Update(),
    new Delete(),
    new Show(),
])]
class BookWithAdminSection extends Book
{
}
